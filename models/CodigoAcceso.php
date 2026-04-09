<?php
// app/models/CodigoAcceso.php

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

    public function getByExamen($id_examen)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_examen = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_examen);
        $stmt->execute();
        $result = $stmt->get_result();
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

    public function generarCodigo($id_examen, $usos_max = 1, $fecha_vencimiento = null)
    {
        // Generar código único
        $codigo = strtoupper(substr(md5(uniqid()), 0, 10));
        
        $query = "INSERT INTO " . $this->table . " (codigo, id_examen, usos_max, fecha_vencimiento) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("siis", $codigo, $id_examen, $usos_max, $fecha_vencimiento);
        
        if ($stmt->execute()) {
            return $codigo;
        }
        return false;
    }

    public function generarMultiples($id_examen, $cantidad, $usos_max = 1, $fecha_vencimiento = null)
    {
        $codigos = [];
        for ($i = 0; $i < $cantidad; $i++) {
            $codigo = $this->generarCodigo($id_examen, $usos_max, $fecha_vencimiento);
            if ($codigo) {
                $codigos[] = $codigo;
            }
        }
        return $codigos;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_codigo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function eliminar($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_codigo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function revocar($id)
    {
        $query = "UPDATE " . $this->table . " SET estado = 'revocado' WHERE id_codigo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>