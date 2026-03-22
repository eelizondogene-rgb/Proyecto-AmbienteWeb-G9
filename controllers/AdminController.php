<?php
class AdminController
{
    private $db;
    private $examenModel;
    private $codigoModel;
    private $resultadoModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->examenModel = new Examen($db);
        $this->codigoModel = new CodigoAcceso($db);
        $this->resultadoModel = new ResultadoExamen($db);
        
        $auth = new AuthController($db);
        $auth->verificarRol('admin');
    }

    public function dashboard()
    {
        $pageTitle = "Panel de Administrador";
        $activePage = "dashboard";
        
        $totalExamenes = $this->examenModel->getTotal();
        $totalCodigos = $this->codigoModel->getTotal();
        $totalResultados = $this->resultadoModel->getTotal();
        $examenesRecientes = $this->examenModel->getRecientes(5);
        
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/admin/dashboard.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function examenes()
    {
        $pageTitle = "Gestión de Exámenes";
        $activePage = "examenes";
        
        $examenes = $this->examenModel->getAll();
        
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/admin/examenes.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function codigos()
    {
        $pageTitle = "Códigos de Acceso";
        $activePage = "codigos";
        
        $codigos = $this->codigoModel->getAll();
        
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/admin/codigos.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function resultados()
    {
        $pageTitle = "Resultados";
        $activePage = "resultados";
        
        $resultados = $this->resultadoModel->getAll();
        
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/admin/resultados.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }
}