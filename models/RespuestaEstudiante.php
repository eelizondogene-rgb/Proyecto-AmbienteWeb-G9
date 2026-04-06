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
        // Si ya respondió esta pregunta, actualizar; si no, insertar
        $query = "SELECT id_respuesta FROM " . $this->table . "
                  WHERE id_sesion = ? AND id_pregunta = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id_sesion, $id_pregunta);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $query = "UPDATE " . $this->table . "
                      SET respuesta_seleccionada = ?, es_correcta = ?, puntaje_obtenido = ?
                      WHERE id_sesion = ? AND id_pregunta = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("siiii", $respuesta, $es_correcta, $puntaje, $id_sesion, $id_pregunta);
        } else {
            $query = "INSERT INTO " . $this->table . "
                      (id_sesion, id_pregunta, respuesta_seleccionada, es_correcta, puntaje_obtenido)
                      VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("iisii", $id_sesion, $id_pregunta, $respuesta, $es_correcta, $puntaje);
        }

        return $stmt->execute();
    }

    public function calcularPuntaje($id_sesion)
    {
        $query = "SELECT SUM(puntaje_obtenido) as total
                  FROM " . $this->table . "
                  WHERE id_sesion = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_sesion);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
}