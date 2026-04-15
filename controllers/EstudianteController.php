<?php
class EstudianteController
{
    private $db;
    private $usuarioModel;
    private $examenModel;
    private $resultadoModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->usuarioModel = new Usuario($db);
        $this->examenModel = new Examen($db);
        $this->resultadoModel = new ResultadoExamen($db);
        
        $auth = new AuthController($db);
        $auth->verificarRol('estudiante');
    }

    public function bienvenida()
    {
        $pageTitle = "Bienvenido";
        $activePage = "bienvenida";
        
        $usuario = $this->usuarioModel->getEstudianteData($_SESSION['usuario']['id_usuario']);
        $examenPendiente = $this->examenModel->getPendientePorEstudiante($_SESSION['usuario']['id_usuario']);
        
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/estudiante/bienvenida.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function perfil()
    {
        $pageTitle = "Mi Perfil";
        $activePage = "perfil";
        
        $usuario = $this->usuarioModel->getEstudianteData($_SESSION['usuario']['id_usuario']);
        
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/estudiante/perfil.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function historial()
    {
        $pageTitle = "Mi Historial";
        $activePage = "historial";
        
        $resultados = $this->resultadoModel->getByEstudiante($_SESSION['usuario']['id_usuario']);
        
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/estudiante/historial.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

public function resultados()
{
    $pageTitle = "Mis Resultados";
    $activePage = "resultados";
    
    $resultadoModel = new ResultadoExamen($this->db);
    $resultados = $resultadoModel->getByEstudiante($_SESSION['usuario']['id_usuario']);
    
    error_log("Resultados encontrados: " . print_r($resultados, true));
    
    include dirname(__DIR__) . '/views/layouts/header.php';
    include dirname(__DIR__) . '/views/estudiante/resultados.php';
    include dirname(__DIR__) . '/views/layouts/footer.php';
}
}