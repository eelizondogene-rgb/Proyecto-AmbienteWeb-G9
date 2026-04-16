<?php
class ResultadoExamen
{
    private $conn;
    private $table = "resultados_examen";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT r.*, u.email, e.nombre as examen_nombre, est.nombre as estudiante_nombre
                  FROM " . $this->table . " r 
                  JOIN sesiones_examen s ON r.id_sesion = s.id_sesion
                  JOIN estudiantes est ON s.id_estudiante = est.id_estudiante
                  JOIN usuarios u ON est.id_usuario = u.id_usuario
                  JOIN examenes e ON s.id_examen = e.id_examen
                  ORDER BY r.fecha_calificacion DESC";
        $result = $this->conn->query($query);
        $resultados = [];
        
        while ($row = $result->fetch_assoc()) {
            $resultados[] = $row;
        }
        return $resultados;
    }

    public function getTotal()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getByEstudiante($id_usuario)
    {
        $query = "SELECT r.*, e.nombre as examen_nombre, e.duracion_minutos
                  FROM " . $this->table . " r 
                  JOIN sesiones_examen s ON r.id_sesion = s.id_sesion
                  JOIN estudiantes est ON s.id_estudiante = est.id_estudiante
                  JOIN examenes e ON s.id_examen = e.id_examen
                  WHERE est.id_usuario = ?
                  ORDER BY r.fecha_calificacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $resultados = [];
        
        while ($row = $result->fetch_assoc()) {
            $resultados[] = $row;
        }
        return $resultados;
    }

    public function getByExamen($id_examen)
    {
        $query = "SELECT r.*, u.email, est.nombre as estudiante_nombre
                  FROM " . $this->table . " r 
                  JOIN sesiones_examen s ON r.id_sesion = s.id_sesion
                  JOIN estudiantes est ON s.id_estudiante = est.id_estudiante
                  JOIN usuarios u ON est.id_usuario = u.id_usuario
                  WHERE s.id_examen = ?
                  ORDER BY r.porcentaje DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_examen);
        $stmt->execute();
        $result = $stmt->get_result();
        $resultados = [];
        
        while ($row = $result->fetch_assoc()) {
            $resultados[] = $row;
        }
        return $resultados;
    }

    public function getPromedioGeneral()
    {
        $query = "SELECT AVG(porcentaje) as promedio FROM " . $this->table;
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return round($row['promedio'] ?? 0, 2);
    }

    public function getEstadisticasPorExamen($id_examen)
    {
        $query = "SELECT 
                    COUNT(*) as total_presentados,
                    AVG(r.porcentaje) as promedio,
                    SUM(CASE WHEN r.estado = 'aprobado' THEN 1 ELSE 0 END) as aprobados,
                    SUM(CASE WHEN r.estado = 'reprobado' THEN 1 ELSE 0 END) as reprobados,
                    MAX(r.porcentaje) as maximo,
                    MIN(r.porcentaje) as minimo
                  FROM " . $this->table . " r
                  JOIN sesiones_examen s ON r.id_sesion = s.id_sesion
                  WHERE s.id_examen = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_examen);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function crear($id_sesion, $puntaje_total, $puntaje_obtenido, $porcentaje, $estado)
    {
        $query = "INSERT INTO " . $this->table . " 
                  (id_sesion, puntaje_total, puntaje_obtenido, porcentaje, estado, fecha_calificacion) 
                  VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiids", $id_sesion, $puntaje_total, $puntaje_obtenido, $porcentaje, $estado);
        return $stmt->execute();
    }

    public function actualizar($id_sesion, $puntaje_total, $puntaje_obtenido, $porcentaje, $estado)
    {
        $query = "UPDATE " . $this->table . " 
                  SET puntaje_total = ?, puntaje_obtenido = ?, porcentaje = ?, estado = ?, fecha_calificacion = NOW() 
                  WHERE id_sesion = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiids", $puntaje_total, $puntaje_obtenido, $porcentaje, $estado, $id_sesion);
        return $stmt->execute();
    }

    public function getBySesion($id_sesion)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_sesion = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_sesion);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
}
?>