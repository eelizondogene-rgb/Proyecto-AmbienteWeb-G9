<?php
session_start();

define('ROOT_PATH', dirname(__DIR__));
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
define('MODELS_PATH', ROOT_PATH . '/models');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('CONFIG_PATH', ROOT_PATH . '/config');

require_once CONFIG_PATH . '/database.php';

function cargarArchivo($path) {
    if (file_exists($path)) {
        require_once $path;
    } else {
        die("Error: Archivo no encontrado: $path");
    }
}

// ==================== MODELOS ====================
cargarArchivo(MODELS_PATH . '/Usuario.php');
cargarArchivo(MODELS_PATH . '/Estudiante.php');
cargarArchivo(MODELS_PATH . '/Administrador.php');
cargarArchivo(MODELS_PATH . '/Examen.php');
cargarArchivo(MODELS_PATH . '/Pregunta.php');
cargarArchivo(MODELS_PATH . '/CodigoAcceso.php');
cargarArchivo(MODELS_PATH . '/AsignacionCodigo.php');
cargarArchivo(MODELS_PATH . '/SesionExamen.php');
cargarArchivo(MODELS_PATH . '/ResultadoExamen.php');
cargarArchivo(MODELS_PATH . '/RespuestaEstudiante.php');
cargarArchivo(MODELS_PATH . '/Reporte.php');

// ==================== CONTROLADORES ====================
cargarArchivo(CONTROLLERS_PATH . '/AuthController.php');
cargarArchivo(CONTROLLERS_PATH . '/AdminController.php');
cargarArchivo(CONTROLLERS_PATH . '/EstudianteController.php');
cargarArchivo(CONTROLLERS_PATH . '/ExamenController.php');
cargarArchivo(CONTROLLERS_PATH . '/ReporteController.php');

$database = new Database();
$db = $database->connect();

$action = $_GET['action'] ?? 'login';

switch ($action) {
   case 'login':
        $controller = new AuthController($db);
        $controller->login();
        break;
        
    case 'logout':
        $controller = new AuthController($db);
        $controller->logout();
        break;
        
   case 'admin_dashboard':
        $controller = new AdminController($db);
        $controller->dashboard();
        break;
        
    case 'admin_examenes':
        $controller = new AdminController($db);
        $controller->examenes();
        break;
        
    case 'admin_preguntas':
        $controller = new AdminController($db);
        $controller->preguntas();
        break;
    case 'admin_detalle_resultado':
        $controller = new AdminController($db);
        $controller->detalleResultado();
        break;
    case 'admin_codigos':
        $controller = new AdminController($db);
        $controller->codigos();
        break;
        
    case 'admin_resultados':
        $controller = new AdminController($db);
        $controller->resultados();
        break;
        
    case 'admin_usuarios':
        $controller = new AdminController($db);
        $controller->usuarios();
        break;
        
    case 'admin_reportes':
        $controller = new ReporteController($db);
        $controller->index();
        break;
  
      case 'estudiante_bienvenida':
        $controller = new EstudianteController($db);
        $controller->bienvenida();
        break;
        
    case 'estudiante_perfil':
        $controller = new EstudianteController($db);
        $controller->perfil();
        break;
        case 'estudiante_actualizar_perfil':
    $controller = new EstudianteController($db);
    $controller->actualizarPerfil();
    break;
        
    case 'estudiante_historial':
        $controller = new EstudianteController($db);
        $controller->historial();
        break;
        
    case 'estudiante_resultados':
        $controller = new EstudianteController($db);
        $controller->resultados();
        break;
    
    case 'examen_realizar':
        $controller = new ExamenController($db);
        $controller->realizar();
        break;
        
    case 'examen_guardar_respuesta':
        $controller = new ExamenController($db);
        $controller->guardarRespuesta();
        break;
        
    case 'examen_finalizar':
        $controller = new ExamenController($db);
        $controller->finalizar();
        break;
        
    case 'acceso_codigo':
        $controller = new ExamenController($db);
        $controller->accesoPorCodigo();
        break;
        
    default:
        header("Location: index.php?action=login");
        break;
}
?>