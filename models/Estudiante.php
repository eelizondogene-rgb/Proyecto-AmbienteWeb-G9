<?php
class Estudiante
{
    private $conn;
    private $table = "estudiantes";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getById($id_estudiante)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_estudiante = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_estudiante);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function getByUsuarioId($id_usuario)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function getIdByUsuario($id_usuario)
    {
        $estudiante = $this->getByUsuarioId($id_usuario);
        return $estudiante ? $estudiante['id_estudiante'] : null;
    }
}
?>