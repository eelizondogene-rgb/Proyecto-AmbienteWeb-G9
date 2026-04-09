<?php
// app/models/Pregunta.php

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
        $query = "SELECT * FROM " . $this->table . " WHERE id_examen = ? ORDER BY orden ASC";
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

    public function getTotalByExamen($id_examen)
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE id_examen = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_examen);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_pregunta = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
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
                  (id_examen, texto, opcion_a, opcion_b, opcion_c, opcion_d, respuesta_correcta, puntos, orden) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issssssii", 
            $data['id_examen'],
            $data['texto'],
            $data['opcion_a'],
            $data['opcion_b'],
            $data['opcion_c'],
            $data['opcion_d'],
            $data['respuesta_correcta'],
            $data['puntos'],
            $data['orden']
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    public function actualizar($id, $data)
    {
        $query = "UPDATE " . $this->table . " 
                  SET texto = ?, opcion_a = ?, opcion_b = ?, opcion_c = ?, opcion_d = ?, 
                      respuesta_correcta = ?, puntos = ?, orden = ? 
                  WHERE id_pregunta = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssssssii", 
            $data['texto'],
            $data['opcion_a'],
            $data['opcion_b'],
            $data['opcion_c'],
            $data['opcion_d'],
            $data['respuesta_correcta'],
            $data['puntos'],
            $data['orden'],
            $id
        );
        return $stmt->execute();
    }

    public function eliminar($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_pregunta = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>