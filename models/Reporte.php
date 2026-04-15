<?php

class Reporte
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getResultadosPorExamen($id_examen = null)
    {
        $query = "SELECT 
                    e.id_examen,
                    e.nombre as examen_nombre,
                    COUNT(DISTINCT s.id_estudiante) as total_estudiantes,
                    COUNT(r.id_resultado) as total_presentados,
                    AVG(r.porcentaje) as promedio,
                    SUM(CASE WHEN r.estado = 'aprobado' THEN 1 ELSE 0 END) as aprobados,
                    SUM(CASE WHEN r.estado = 'reprobado' THEN 1 ELSE 0 END) as reprobados,
                    MAX(r.porcentaje) as nota_maxima,
                    MIN(r.porcentaje) as nota_minima
                  FROM examenes e
                  LEFT JOIN sesiones_examen s ON e.id_examen = s.id_examen
                  LEFT JOIN resultados_examen r ON s.id_sesion = r.id_sesion
                  WHERE e.estado != 'borrador'";
        
        if ($id_examen) {
            $query .= " AND e.id_examen = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id_examen);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $query .= " GROUP BY e.id_examen ORDER BY e.created_at DESC";
            $result = $this->conn->query($query);
        }
        
        $reportes = [];
        while ($row = $result->fetch_assoc()) {
            $reportes[] = $row;
        }
        return $reportes;
    }

    public function getRendimientoEstudiantes($limite = 10)
    {
        $query = "SELECT 
                    u.id_usuario,
                    u.email,
                    est.nombre,
                    est.apellidos,
                    COUNT(r.id_resultado) as examenes_presentados,
                    AVG(r.porcentaje) as promedio_general,
                    SUM(CASE WHEN r.estado = 'aprobado' THEN 1 ELSE 0 END) as aprobados,
                    SUM(CASE WHEN r.estado = 'reprobado' THEN 1 ELSE 0 END) as reprobados
                  FROM usuarios u
                  JOIN estudiantes est ON u.id_usuario = est.id_usuario
                  LEFT JOIN sesiones_examen s ON est.id_estudiante = s.id_estudiante
                  LEFT JOIN resultados_examen r ON s.id_sesion = r.id_sesion
                  WHERE u.rol = 'estudiante'
                  GROUP BY u.id_usuario
                  ORDER BY promedio_general DESC
                  LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $reportes = [];
        while ($row = $result->fetch_assoc()) {
            $reportes[] = $row;
        }
        return $reportes;
    }

    public function getUsoCodigos()
    {
        $query = "SELECT 
                    c.codigo,
                    e.nombre as examen_nombre,
                    c.usos_max,
                    c.usos_actuales,
                    c.estado,
                    c.fecha_vencimiento,
                    CASE WHEN a.id_asignacion IS NOT NULL THEN 'Asignado' ELSE 'No asignado' END as asignado
                  FROM codigos_acceso c
                  JOIN examenes e ON c.id_examen = e.id_examen
                  LEFT JOIN asignacion_codigo a ON c.id_codigo = a.id_codigo
                  ORDER BY c.created_at DESC";
        $result = $this->conn->query($query);
        
        $reportes = [];
        while ($row = $result->fetch_assoc()) {
            $reportes[] = $row;
        }
        return $reportes;
    }

    public function getEstadisticasGenerales()
    {
        $stats = [];
        
        $query = "SELECT COUNT(*) as total FROM examenes";
        $result = $this->conn->query($query);
        $stats['total_examenes'] = $result->fetch_assoc()['total'];
        
        $query = "SELECT COUNT(*) as total FROM usuarios WHERE rol = 'estudiante'";
        $result = $this->conn->query($query);
        $stats['total_estudiantes'] = $result->fetch_assoc()['total'];
        
        $query = "SELECT COUNT(*) as total FROM codigos_acceso";
        $result = $this->conn->query($query);
        $stats['total_codigos'] = $result->fetch_assoc()['total'];
        
        $query = "SELECT COUNT(*) as total FROM resultados_examen";
        $result = $this->conn->query($query);
        $stats['total_completados'] = $result->fetch_assoc()['total'];
        
        $query = "SELECT AVG(porcentaje) as promedio FROM resultados_examen";
        $result = $this->conn->query($query);
        $stats['promedio_general'] = round($result->fetch_assoc()['promedio'] ?? 0, 2);
        
        // Tasa de aprobación
        $query = "SELECT 
                    SUM(CASE WHEN estado = 'aprobado' THEN 1 ELSE 0 END) as aprobados,
                    COUNT(*) as total
                  FROM resultados_examen";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        $stats['tasa_aprobacion'] = $row['total'] > 0 ? round(($row['aprobados'] / $row['total']) * 100, 2) : 0;
        
        return $stats;
    }

    public function getExamenesPorMes($anio = null)
    {
        if (!$anio) {
            $anio = date('Y');
        }
        
        $query = "SELECT 
                    MONTH(created_at) as mes,
                    COUNT(*) as cantidad
                  FROM examenes
                  WHERE YEAR(created_at) = ?
                  GROUP BY MONTH(created_at)
                  ORDER BY mes";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $anio);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $datos = [];
        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }
        return $datos;
    }

    public function getDistribucionResultados($id_examen)
    {
        $query = "SELECT 
                    r.estado,
                    COUNT(*) as cantidad
                  FROM resultados_examen r
                  JOIN sesiones_examen s ON r.id_sesion = s.id_sesion
                  WHERE s.id_examen = ?
                  GROUP BY r.estado";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_examen);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $datos = ['aprobados' => 0, 'reprobados' => 0, 'pendiente_revision' => 0];
        while ($row = $result->fetch_assoc()) {
            if ($row['estado'] == 'aprobado') $datos['aprobados'] = $row['cantidad'];
            if ($row['estado'] == 'reprobado') $datos['reprobados'] = $row['cantidad'];
            if ($row['estado'] == 'pendiente_revision') $datos['pendiente_revision'] = $row['cantidad'];
        }
        return $datos;
    }
}
?>