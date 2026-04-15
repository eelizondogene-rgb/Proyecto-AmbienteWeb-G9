<?php

class ReporteController
{
    private $db;
    private $reporteModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->reporteModel = new Reporte($db);
        
        $auth = new AuthController($db);
        $auth->verificarRol('admin');
    }

    public function index()
    {
        $pageTitle = "Reportes y Estadísticas";
        $activePage = "reportes";
        $additionalJs = ['chart', 'reportes'];
        
        $estadisticas = $this->reporteModel->getEstadisticasGenerales();
        $resultadosExamenes = $this->reporteModel->getResultadosPorExamen();
        $topEstudiantes = $this->reporteModel->getRendimientoEstudiantes(10);
        $usoCodigos = $this->reporteModel->getUsoCodigos();
        $examenesPorMes = $this->reporteModel->getExamenesPorMes();
        
        include dirname(__DIR__) . '/views/layouts/header.php';
        include dirname(__DIR__) . '/views/admin/reportes.php';
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }

    public function getDatosGrafica()
    {
        $tipo = $_GET['tipo'] ?? 'examenes_mes';
        $id_examen = $_GET['id_examen'] ?? 0;
        
        header('Content-Type: application/json');
        
        switch ($tipo) {
            case 'examenes_mes':
                $datos = $this->reporteModel->getExamenesPorMes();
                break;
            case 'resultados_examen':
                $datos = $this->reporteModel->getDistribucionResultados($id_examen);
                break;
            default:
                $datos = [];
        }
        
        echo json_encode($datos);
        exit;
    }
}
?>