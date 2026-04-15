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

       $puntajeTotal = 0;
        foreach ($preguntas as $pregunta) {
            $puntajeTotal += $pregunta['puntos'];
        }

       $idEstudiante = $_SESSION['id_estudiante'] ?? null;
        $idCodigo = $_SESSION['id_codigo'] ?? null;
        
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
        $puntaje    = $esCorrecta ? ($pregunta['puntos'] ?? 1) : 0;

        $respuestaModel = new RespuestaEstudiante($this->db);
        $resultado = $respuestaModel->guardar($idSesion, $idPregunta, $respuesta, $esCorrecta, $puntaje);

        if ($resultado) {
            echo json_encode(['response' => '00', 'es_correcta' => $esCorrecta]);
        } else {
            echo json_encode(['response' => '03', 'message' => 'Error al guardar']);
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

      $sesionModel = new SesionExamen($this->db);
        $sesionModel->finalizar($idSesion);

       $respuestaModel = new RespuestaEstudiante($this->db);
        $puntajeObtenido = $respuestaModel->calcularPuntaje($idSesion);
        
       $preguntaModel = new Pregunta($this->db);
        $preguntas = $preguntaModel->getByExamen($idExamen);
        $puntajeTotal = 0;
        foreach ($preguntas as $pregunta) {
            $puntajeTotal += $pregunta['puntos'];
        }
        
       if ($puntajeTotal == 0) {
            $puntajeTotal = 1;
        }

        $porcentaje = round(($puntajeObtenido / $puntajeTotal) * 100, 2);
        $estado = $porcentaje >= 70 ? 'aprobado' : 'reprobado';

       $resultadoModel = new ResultadoExamen($this->db);
        
         $resultadoExistente = $resultadoModel->getBySesion($idSesion);
        
        if ($resultadoExistente) {
             $resultadoModel->actualizar($idSesion, $puntajeTotal, $puntajeObtenido, $porcentaje, $estado);
        } else {
           $resultadoModel->crear($idSesion, $puntajeTotal, $puntajeObtenido, $porcentaje, $estado);
        }

      unset($_SESSION['id_sesion']);
        unset($_SESSION['id_codigo']);

        $_SESSION['success'] = "Examen finalizado correctamente. Puntaje: " . $puntajeObtenido . "/" . $puntajeTotal . " (" . $porcentaje . "%)";
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

            $codigoModel = new CodigoAcceso($this->db);
            
           $diagnostico = $codigoModel->diagnosticarCodigo($codigo);

            if (!$diagnostico) {
                $_SESSION['error'] = "Código no existe en la base de datos";
                header("Location: index.php?action=login");
                exit;
            }

            if ($diagnostico['razon'] !== 'VALIDO') {
                $_SESSION['error'] = "❌ " . $diagnostico['razon'];
                header("Location: index.php?action=login");
                exit;
            }

            $codigoData = $diagnostico;

            $asignacionModel = new AsignacionCodigo($this->db);
            $asignacion = $asignacionModel->getByCodigo($codigoData['id_codigo']);

            if (!$asignacion) {
                $_SESSION['error'] = "Este código no está asignado a ningún estudiante";
                header("Location: index.php?action=login");
                exit;
            }

            $estudianteModel = new Estudiante($this->db);
            $estudiante = $estudianteModel->getById($asignacion['id_estudiante']);
            
            if (!$estudiante) {
                $_SESSION['error'] = "Estudiante no encontrado";
                header("Location: index.php?action=login");
                exit;
            }
            
            $usuarioModel = new Usuario($this->db);
            $usuario = $usuarioModel->getById($estudiante['id_usuario']);

            if (!$usuario) {
                $_SESSION['error'] = "Usuario del estudiante no encontrado";
                header("Location: index.php?action=login");
                exit;
            }

     $_SESSION['usuario'] = [
                'id_usuario' => $usuario['id_usuario'],
                'email' => $usuario['email'],
                'rol' => 'estudiante'
            ];
            $_SESSION['id_estudiante'] = $estudiante['id_estudiante'];
            $_SESSION['id_codigo'] = $codigoData['id_codigo'];

        $codigoModel->incrementarUso($codigoData['id_codigo']);

            header("Location: index.php?action=examen_realizar&id=" . $codigoData['id_examen']);
            exit;
        }

        header("Location: index.php?action=login");
        exit;
    }
}
?>