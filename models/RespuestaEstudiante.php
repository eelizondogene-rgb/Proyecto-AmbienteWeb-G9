<?php
class RespuestaEstudiante
{
    private $conn;
    private $table = "respuestas_estudiante";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function guardar($id_sesion, $id_pregunta, $respuesta, $es_correcta, $puntaje)
    {
        $es_correcta_int = $es_correcta ? 1 : 0;
        
        $checkQuery = "SELECT id_respuesta FROM " . $this->table . " WHERE id_sesion = ? AND id_pregunta = ?";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bind_param("ii", $id_sesion, $id_pregunta);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows > 0) {
       
            $query = "UPDATE " . $this->table . " 
                      SET respuesta_seleccionada = ?, es_correcta = ?, puntaje_obtenido = ? 
                      WHERE id_sesion = ? AND id_pregunta = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("siiii", $respuesta, $es_correcta_int, $puntaje, $id_sesion, $id_pregunta);
        } else {
          
            $query = "INSERT INTO " . $this->table . " 
                      (id_sesion, id_pregunta, respuesta_seleccionada, es_correcta, puntaje_obtenido) 
                      VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("iisii", $id_sesion, $id_pregunta, $respuesta, $es_correcta_int, $puntaje);
        }
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function calcularPuntaje($id_sesion)
    {
        $query = "SELECT SUM(puntaje_obtenido) as total FROM " . $this->table . " WHERE id_sesion = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_sesion);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public function getRespuestasBySesion($id_sesion)
    {
        $query = "SELECT r.*, p.texto, p.respuesta_correcta, p.puntos
                  FROM " . $this->table . " r 
                  JOIN preguntas p ON r.id_pregunta = p.id_pregunta 
                  WHERE r.id_sesion = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_sesion);
        $stmt->execute();
        $result = $stmt->get_result();
        $respuestas = [];
        
        while ($row = $result->fetch_assoc()) {
            $respuestas[] = $row;
        }
        return $respuestas;
    }
}
?>