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
    
    if ($usuario && !isset($_SESSION['id_estudiante'])) {
        $_SESSION['id_estudiante'] = $usuario['id_estudiante'];
    }
    
    $examenPendiente = $this->examenModel->getPendientePorEstudiante($_SESSION['usuario']['id_usuario']);
    
    if (!$examenPendiente && isset($_SESSION['id_examen_pendiente'])) {
        $examenPendiente = $this->examenModel->getById($_SESSION['id_examen_pendiente']);
    }
    
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
    public function actualizarPerfil()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_usuario = $_SESSION['usuario']['id_usuario'];
        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'apellidos' => $_POST['apellidos'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null
        ];
        
        if ($this->usuarioModel->actualizarEstudiante($id_usuario, $data)) {
            $_SESSION['success'] = "Perfil actualizado correctamente";
        } else {
            $_SESSION['error'] = "Error al actualizar el perfil";
        }
    }
    header("Location: index.php?action=estudiante_perfil");
    exit;
}

    public function historial()
{
    $pageTitle = "Mi Historial";
    $activePage = "historial";
    
    $resultadoModel = new ResultadoExamen($this->db);
    $resultados = $resultadoModel->getByEstudiante($_SESSION['usuario']['id_usuario']);
    
    $totalExamenes = count($resultados);
    $totalAprobados = 0;
    $totalReprobados = 0;
    $sumaPorcentajes = 0;
    
    foreach ($resultados as $r) {
        if (($r['porcentaje'] ?? 0) >= 70) {
            $totalAprobados++;
        } else {
            $totalReprobados++;
        }
        $sumaPorcentajes += ($r['porcentaje'] ?? 0);
    }
    
    $promedioGeneral = $totalExamenes > 0 ? round($sumaPorcentajes / $totalExamenes, 1) : 0;
    
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