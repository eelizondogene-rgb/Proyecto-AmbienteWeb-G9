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
        $query = "SELECT r.*, u.email, e.nombre as examen_nombre 
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
}