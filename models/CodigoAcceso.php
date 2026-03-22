<?php
class CodigoAcceso
{
    private $conn;
    private $table = "codigos_acceso";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT c.*, e.nombre as examen_nombre 
                  FROM " . $this->table . " c 
                  LEFT JOIN examenes e ON c.id_examen = e.id_examen 
                  ORDER BY c.created_at DESC";
        $result = $this->conn->query($query);
        $codigos = [];
        
        while ($row = $result->fetch_assoc()) {
            $codigos[] = $row;
        }
        
        return $codigos;
    }

    public function getTotal()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function validarCodigo($codigo)
    {
        $query = "SELECT c.*, e.* FROM " . $this->table . " c 
                  JOIN examenes e ON c.id_examen = e.id_examen 
                  WHERE c.codigo = ? 
                  AND c.estado = 'disponible' 
                  AND (c.fecha_vencimiento IS NULL OR c.fecha_vencimiento > NOW())
                  AND c.usos_actuales < c.usos_max";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
}