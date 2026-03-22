<?php
class Usuario
{
    private $conn;
    private $table = "usuarios";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function login($email, $password)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE email = ? AND activo = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (md5($password) === $user['contraseña']) {
                return $user;
            }
        }
        return false;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function getEstudianteData($id_usuario)
    {
        $query = "SELECT u.*, e.nombre, e.apellidos, e.fecha_nacimiento, e.telefono 
                  FROM usuarios u 
                  LEFT JOIN estudiantes e ON u.id_usuario = e.id_usuario 
                  WHERE u.id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function registrarAcceso($id_usuario)
    {
        $query = "UPDATE " . $this->table . " SET ultimo_acceso = NOW() WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        return $stmt->execute();
    }
}