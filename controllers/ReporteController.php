<?php
class ReporteController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
        
        $auth = new AuthController($db);
        $auth->verificarRol('admin');
    }

    public function index()
    {
        $pageTitle = "Reportes";
        $activePage = "reportes";
        $additionalJs = ['reportes'];
        
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/admin/reportes.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }
}