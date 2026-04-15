<?php
class SesionExamen
{
    private $conn;
    private $table = "sesiones_examen";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function iniciar($id_estudiante, $id_examen, $id_codigo = null)
    {
        if (!$id_estudiante || !$id_examen) {
            return false;
        }
        
        if (!$id_codigo) {
            $query = "SELECT c.id_codigo FROM codigos_acceso c 
                      JOIN asignacion_codigo a ON c.id_codigo = a.id_codigo 
                      WHERE a.id_estudiante = ? AND c.id_examen = ? 
                      AND c.estado = 'disponible' 
                      LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $id_estudiante, $id_examen);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                $id_codigo = $row['id_codigo'];
            } else {
                return false;
            }
        }
        
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

        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $query = "INSERT INTO " . $this->table . "
                  (id_estudiante, id_examen, id_codigo, ip_address, user_agent, estado, fecha_inicio)
                  VALUES (?, ?, ?, ?, ?, 'en_curso', NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiiss", $id_estudiante, $id_examen, $id_codigo, $ip, $userAgent);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }

    public function finalizar($id_sesion)
    {
        $query = "UPDATE " . $this->table . "
                  SET estado = 'finalizado', fecha_fin = NOW(),
                      tiempo_transcurrido = TIMESTAMPDIFF(SECOND, fecha_inicio, NOW())
                  WHERE id_sesion = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_sesion);
        return $stmt->execute();
    }

    public function getById($id_sesion)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_sesion = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_sesion);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
}
?>