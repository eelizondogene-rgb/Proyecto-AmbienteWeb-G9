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
        
        $pageTitle = $examen['nombre'];
        $activePage = "examen";
        $additionalJs = ['examen'];
        
        include dirname(__DIR__) . '/views/layouts/header_examen.php';
        include dirname(__DIR__) . '/views/examenes/realizar.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function finalizar()
    {
        $auth = new AuthController($this->db);
        $auth->verificarSesion();
        
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