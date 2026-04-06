<?php
class Pregunta
{
    private $conn;
    private $table = "preguntas";
 
    public function __construct($db)
    {
        $this->conn = $db;
    }
 
    public function getByExamen($id_examen)
    {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table . " p
                  LEFT JOIN categorias_pregunta c ON p.id_categoria = c.id_categoria
                  WHERE p.id_examen = ?
                  ORDER BY p.orden ASC";
 
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_examen);
        $stmt->execute();
        $result = $stmt->get_result();
 
        $preguntas = [];
        while ($row = $result->fetch_assoc()) {
            $preguntas[] = $row;
        }
 
        return $preguntas;
    }
 
    public function getById($id_pregunta)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_pregunta = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_pregunta);
        $stmt->execute();
        $result = $stmt->get_result();
 
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
 
    public function getTotalPorExamen($id_examen)
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE id_examen = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_examen);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
 