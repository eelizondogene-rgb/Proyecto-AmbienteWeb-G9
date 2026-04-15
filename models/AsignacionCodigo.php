<?php
class AsignacionCodigo
{
    private $conn;
    private $table = "asignacion_codigo";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getByCodigo($id_codigo)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_codigo = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_codigo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function getByEstudiante($id_estudiante)
    {
        $query = "SELECT a.*, c.codigo, c.id_examen, e.nombre as examen_nombre 
                  FROM " . $this->table . " a 
                  JOIN codigos_acceso c ON a.id_codigo = c.id_codigo 
                  JOIN examenes e ON c.id_examen = e.id_examen 
                  WHERE a.id_estudiante = ?
                  ORDER BY a.fecha_asignacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_estudiante);
        $stmt->execute();
        $result = $stmt->get_result();
        $asignaciones = [];
        
        while ($row = $result->fetch_assoc()) {
            $asignaciones[] = $row;
        }
        return $asignaciones;
    }

    public function crear($id_estudiante, $id_codigo)
    {
        // Verificar que los IDs sean números válidos
        if (!is_numeric($id_estudiante) || !is_numeric($id_codigo)) {
            error_log("Error: IDs inválidos - estudiante: $id_estudiante, codigo: $id_codigo");
            return false;
        }
        
        $id_estudiante = intval($id_estudiante);
        $id_codigo = intval($id_codigo);
        
        $query = "INSERT INTO " . $this->table . " (id_estudiante, id_codigo) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id_estudiante, $id_codigo);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    public function eliminar($id_asignacion)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_asignacion = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_asignacion);
        return $stmt->execute();
    }
}
?>