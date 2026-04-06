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

        // Cargar preguntas reales desde la base de datos
        $preguntaModel = new Pregunta($this->db);
        $preguntas = $preguntaModel->getByExamen($idExamen);

        // Crear la sesion del examen si no existe
        $idEstudiante = $_SESSION['id_estudiante'] ?? null;
        if ($idEstudiante) {
            $sesionModel = new SesionExamen($this->db);
            $idSesion = $sesionModel->iniciar($idEstudiante, $idExamen);
            $_SESSION['id_sesion'] = $idSesion;
        }

        $pageTitle = $examen['nombre'];
        $activePage = "examen";

        include dirname(__DIR__) . '/views/layouts/header_examen.php';
        include dirname(__DIR__) . '/views/layouts/examenes/realizar.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function guardarRespuesta()
    {
        // Responde JSON para el $.post de jQuery (igual que la profe)
        $auth = new AuthController($this->db);
        $auth->verificarSesion();

        $idSesion    = $_POST['id_sesion']   ?? 0;
        $idPregunta  = $_POST['id_pregunta'] ?? 0;
        $respuesta   = $_POST['respuesta']   ?? null;

        if (!$idSesion || !$idPregunta) {
            echo json_encode(['response' => '01', 'message' => 'Datos incompletos']);
            exit;
        }

        // Verificar si la respuesta es correcta
        $preguntaModel = new Pregunta($this->db);
        $pregunta = $preguntaModel->getById($idPregunta);

        $esCorrecta = ($pregunta && $respuesta === $pregunta['respuesta_correcta']);
        $puntaje    = $esCorrecta ? ($pregunta['puntos'] ?? 1) : 0;

        $respuestaModel = new RespuestaEstudiante($this->db);
        $respuestaModel->guardar($idSesion, $idPregunta, $respuesta, $esCorrecta, $puntaje);

        echo json_encode(['response' => '00', 'es_correcta' => $esCorrecta]);
        exit;
    }

    public function finalizar()
    {
        $auth = new AuthController($this->db);
        $auth->verificarSesion();

        $idExamen = $_POST['id_examen'] ?? 0;
        $idSesion = $_SESSION['id_sesion'] ?? 0;

        if ($idSesion) {
            // Cerrar la sesion del examen
            $sesionModel = new SesionExamen($this->db);
            $sesionModel->finalizar($idSesion);

            // Calcular y guardar el resultado
            $respuestaModel = new RespuestaEstudiante($this->db);
            $puntajeObtenido = $respuestaModel->calcularPuntaje($idSesion);

            $examenModel = new Examen($this->db);
            $examen = $examenModel->getById($idExamen);
            $puntajeTotal = $examen['puntaje_maximo'] ?? 100;

            $porcentaje = $puntajeTotal > 0
                ? round(($puntajeObtenido / $puntajeTotal) * 100, 2)
                : 0;

            $estado = $porcentaje >= 70 ? 'aprobado' : 'reprobado';

            $resultadoModel = new ResultadoExamen($this->db);
            $resultadoModel->crear($idSesion, $puntajeTotal, $puntajeObtenido, $porcentaje, $estado);

            unset($_SESSION['id_sesion']);
        }

        $_SESSION['success'] = "Examen finalizado correctamente";
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
            $examen = $codigoModel->validarCodigo($codigo);

            if ($examen) {
                header("Location: index.php?action=examen_realizar&id=" . $examen['id_examen']);
                exit;
            } else {
                $_SESSION['error'] = "Código inválido o expirado";
                header("Location: index.php?action=login");
                exit;
            }
        }

        header("Location: index.php?action=login");
        exit;
    }
}