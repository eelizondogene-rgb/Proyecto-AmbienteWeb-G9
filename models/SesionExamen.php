<?php
class SesionExamen
{
    private $conn;
    private $table = "sesiones_examen";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function iniciar($id_estudiante, $id_examen)
    {
        // Evitar sesiones duplicadas
        $query = "SELECT id_sesion FROM " . $this->table . "
                  WHERE id_estudiante = ? AND id_examen = ? AND estado = 'en_curso'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id_estudiante, $id_examen);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id_sesion'];
        }

        // Crear nueva sesion
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $query = "INSERT INTO " . $this->table . "
                  (id_estudiante, id_examen, id_codigo, ip_address, estado)
                  VALUES (?, ?, 1, ?, 'en_curso')";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iis", $id_estudiante, $id_examen, $ip);
        $stmt->execute();

        return $this->conn->insert_id;
    }

    public function finalizar($id_sesion)
    {
        $query = "UPDATE " . $this->table . "
                  SET estado = 'finalizado', fecha_fin = NOW()
                  WHERE id_sesion = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_sesion);
        return $stmt->execute();
    }
}