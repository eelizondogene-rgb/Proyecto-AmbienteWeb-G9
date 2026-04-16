<?php
class ExamenController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function realizar()
    {
        $auth = new AuthController($this->db);
        $auth->verificarSesion();

        $idExamen = $_GET['id'] ?? 0;

        if (!$idExamen) {
            header("Location: index.php?action=estudiante_bienvenida");
            exit;
        }

        $examenModel = new Examen($this->db);
        $examen = $examenModel->getById($idExamen);

        if (!$examen) {
            header("Location: index.php?action=estudiante_bienvenida");
            exit;
        }

        $preguntaModel = new Pregunta($this->db);
        $preguntas = $preguntaModel->getByExamen($idExamen);

        $idEstudiante = $_SESSION['id_estudiante'] ?? null;
        $idCodigo = $_SESSION['id_codigo'] ?? null;
        
        if (!$idEstudiante && isset($_SESSION['usuario'])) {
            $estudianteModel = new Estudiante($this->db);
            $estudiante = $estudianteModel->getByUsuarioId($_SESSION['usuario']['id_usuario']);
            if ($estudiante) {
                $idEstudiante = $estudiante['id_estudiante'];
                $_SESSION['id_estudiante'] = $idEstudiante;
            }
        }
        
        $idSesion = 0;
        
        if ($idEstudiante && $idExamen) {
            $sesionModel = new SesionExamen($this->db);
            
            if (!$idCodigo) {
                $query = "SELECT c.id_codigo FROM codigos_acceso c 
                          JOIN asignacion_codigo a ON c.id_codigo = a.id_codigo 
                          WHERE a.id_estudiante = ? AND c.id_examen = ? 
                          AND c.estado = 'disponible' 
                          LIMIT 1";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param("ii", $idEstudiante, $idExamen);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($row = $result->fetch_assoc()) {
                    $idCodigo = $row['id_codigo'];
                    $_SESSION['id_codigo'] = $idCodigo;
                }
            }
            
            $idSesion = $sesionModel->iniciar($idEstudiante, $idExamen, $idCodigo);
            $_SESSION['id_sesion'] = $idSesion;
        }

        $pageTitle = $examen['nombre'];
        $activePage = "examen";
        $additionalCss = ['examen'];

        include dirname(__DIR__) . '/views/layouts/header_examen.php';
        include dirname(__DIR__) . '/views/examenes/realizar.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function guardarRespuesta()
    {
        $auth = new AuthController($this->db);
        $auth->verificarSesion();

        $idSesion    = $_POST['id_sesion']   ?? 0;
        $idPregunta  = $_POST['id_pregunta'] ?? 0;
        $respuesta   = $_POST['respuesta']   ?? null;

        header('Content-Type: application/json');

        if (!$idSesion || !$idPregunta) {
            echo json_encode(['response' => '01', 'message' => 'Datos incompletos']);
            exit;
        }

        $preguntaModel = new Pregunta($this->db);
        $pregunta = $preguntaModel->getById($idPregunta);

        if (!$pregunta) {
            echo json_encode(['response' => '02', 'message' => 'Pregunta no encontrada']);
            exit;
        }

        $esCorrecta = ($respuesta === $pregunta['respuesta_correcta']);
        $puntaje    = $esCorrecta ? (int)($pregunta['puntos'] ?? 1) : 0;
        $esCorrectaInt = $esCorrecta ? 1 : 0;

        $checkQuery = "SELECT id_respuesta FROM respuestas_estudiante WHERE id_sesion = ? AND id_pregunta = ?";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->bind_param("ii", $idSesion, $idPregunta);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $row = $checkResult->fetch_assoc();
            $query = "UPDATE respuestas_estudiante 
                      SET respuesta_seleccionada = ?, es_correcta = ?, puntaje_obtenido = ? 
                      WHERE id_respuesta = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("siii", $respuesta, $esCorrectaInt, $puntaje, $row['id_respuesta']);
        } else {
            $query = "INSERT INTO respuestas_estudiante 
                      (id_sesion, id_pregunta, respuesta_seleccionada, es_correcta, puntaje_obtenido) 
                      VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("iisii", $idSesion, $idPregunta, $respuesta, $esCorrectaInt, $puntaje);
        }

        if ($stmt->execute()) {
            echo json_encode(['response' => '00', 'es_correcta' => $esCorrecta]);
        } else {
            echo json_encode(['response' => '03', 'message' => 'Error al guardar: ' . $stmt->error]);
        }
        exit;
    }

    public function finalizar()
    {
        $auth = new AuthController($this->db);
        $auth->verificarSesion();

        $idExamen = $_POST['id_examen'] ?? 0;
        $idSesion = $_POST['id_sesion'] ?? $_SESSION['id_sesion'] ?? 0;

        if (!$idSesion) {
            $_SESSION['error'] = "No se encontró la sesión del examen";
            header("Location: index.php?action=estudiante_bienvenida");
            exit;
        }

        $query = "UPDATE sesiones_examen SET estado = 'finalizado', fecha_fin = NOW() WHERE id_sesion = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idSesion);
        $stmt->execute();

        $query = "SELECT SUM(puntaje_obtenido) as total FROM respuestas_estudiante WHERE id_sesion = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idSesion);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $puntajeObtenido = (int)($row['total'] ?? 0);
        
        $query = "SELECT SUM(puntos) as total FROM preguntas WHERE id_examen = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idExamen);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $puntajeTotal = (int)($row['total'] ?? 1);
        
        $porcentaje = $puntajeTotal > 0 ? round(($puntajeObtenido / $puntajeTotal) * 100, 2) : 0;
        $estado = $porcentaje >= 70 ? 'aprobado' : 'reprobado';

        $query = "INSERT INTO resultados_examen (id_sesion, puntaje_total, puntaje_obtenido, porcentaje, estado, fecha_calificacion) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iiids", $idSesion, $puntajeTotal, $puntajeObtenido, $porcentaje, $estado);
        $stmt->execute();

        unset($_SESSION['id_sesion']);
        unset($_SESSION['id_codigo']);

        $_SESSION['success'] = "Examen finalizado. Puntaje: " . $puntajeObtenido . "/" . $puntajeTotal . " (" . $porcentaje . "%)";
        header("Location: index.php?action=estudiante_resultados");
        exit;
    }

    public function accesoPorCodigo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigo = $_POST['codigo'] ?? '';

            if (empty($codigo)) {
                $_SESSION['error'] = "El código es requerido";
                header("Location: index.php?action=login");
                exit;
            }

            $query = "SELECT c.*, e.id_examen FROM codigos_acceso c JOIN examenes e ON c.id_examen = e.id_examen WHERE c.codigo = ? AND c.estado = 'disponible' AND e.estado = 'activo'";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("s", $codigo);
            $stmt->execute();
            $result = $stmt->get_result();
            $codigoData = $result->fetch_assoc();

            if (!$codigoData) {
                $_SESSION['error'] = "Código inválido o expirado";
                header("Location: index.php?action=login");
                exit;
            }

            $query = "SELECT a.*, e.id_estudiante FROM asignacion_codigo a JOIN estudiantes e ON a.id_estudiante = e.id_estudiante WHERE a.id_codigo = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $codigoData['id_codigo']);
            $stmt->execute();
            $result = $stmt->get_result();
            $asignacion = $result->fetch_assoc();

            if (!$asignacion) {
                $_SESSION['error'] = "Código no asignado a ningún estudiante";
                header("Location: index.php?action=login");
                exit;
            }

            $query = "SELECT u.* FROM usuarios u JOIN estudiantes e ON u.id_usuario = e.id_usuario WHERE e.id_estudiante = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $asignacion['id_estudiante']);
            $stmt->execute();
            $result = $stmt->get_result();
            $usuario = $result->fetch_assoc();

            $_SESSION['usuario'] = [
                'id_usuario' => $usuario['id_usuario'],
                'email' => $usuario['email'],
                'rol' => 'estudiante'
            ];
            $_SESSION['id_estudiante'] = $asignacion['id_estudiante'];
            $_SESSION['id_codigo'] = $codigoData['id_codigo'];
            $_SESSION['id_examen_pendiente'] = $codigoData['id_examen'];

            $query = "UPDATE codigos_acceso SET usos_actuales = usos_actuales + 1 WHERE id_codigo = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $codigoData['id_codigo']);
            $stmt->execute();

            header("Location: index.php?action=estudiante_bienvenida");
            exit;
        }

        header("Location: index.php?action=login");
        exit;
    }
}
?>