<?php

class Examen
{
    private $conn;
    private $table = "examenes";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $result = $this->conn->query($query);
        $examenes = [];
        
        while ($row = $result->fetch_assoc()) {
            $examenes[] = $row;
        }
        return $examenes;
    }

    public function getRecientes($limit = 5)
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $examenes = [];
        
        while ($row = $result->fetch_assoc()) {
            $examenes[] = $row;
        }
        return $examenes;
    }

    public function getTotal()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_examen = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

public function getPendientePorEstudiante($id_usuario)
{
    $query = "SELECT e.* FROM examenes e 
              JOIN codigos_acceso c ON e.id_examen = c.id_examen 
              JOIN asignacion_codigo a ON c.id_codigo = a.id_codigo 
              JOIN estudiantes est ON a.id_estudiante = est.id_estudiante 
              WHERE est.id_usuario = ? 
              AND e.estado = 'activo' 
              AND c.estado = 'disponible' 
              AND (c.fecha_vencimiento IS NULL OR c.fecha_vencimiento > NOW())
              AND c.usos_actuales <= c.usos_max
              AND NOT EXISTS (
                  SELECT 1 FROM sesiones_examen s 
                  WHERE s.id_estudiante = est.id_estudiante 
                  AND s.id_examen = e.id_examen 
                  AND s.estado = 'finalizado'
              )
              LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

    public function crear($data)
    {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, descripcion, duracion_minutos, fecha_inicio, fecha_cierre, estado, creado_por) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssisssi", 
            $data['nombre'], 
            $data['descripcion'], 
            $data['duracion_minutos'],
            $data['fecha_inicio'],
            $data['fecha_cierre'],
            $data['estado'],
            $data['creado_por']
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    public function actualizar($id, $data)
    {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = ?, descripcion = ?, duracion_minutos = ?, 
                      fecha_inicio = ?, fecha_cierre = ?, estado = ?, updated_at = NOW() 
                  WHERE id_examen = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssisssi", 
            $data['nombre'], 
            $data['descripcion'], 
            $data['duracion_minutos'],
            $data['fecha_inicio'],
            $data['fecha_cierre'],
            $data['estado'],
            $id
        );
        return $stmt->execute();
    }

    public function eliminar($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_examen = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function cambiarEstado($id, $estado)
    {
        $query = "UPDATE " . $this->table . " SET estado = ? WHERE id_examen = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $estado, $id);
        return $stmt->execute();
    }
}
?>