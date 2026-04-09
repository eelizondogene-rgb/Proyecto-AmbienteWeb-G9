<?php
// app/models/Usuario.php

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

    public function getAllEstudiantes()
    {
        $query = "SELECT u.*, e.nombre, e.apellidos, e.fecha_nacimiento, e.telefono 
                  FROM usuarios u 
                  JOIN estudiantes e ON u.id_usuario = e.id_usuario 
                  WHERE u.rol = 'estudiante'
                  ORDER BY u.fecha_registro DESC";
        $result = $this->conn->query($query);
        $usuarios = [];
        
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
        return $usuarios;
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

    public function crearEstudiante($data)
    {
        // Primero crear usuario
        $queryUser = "INSERT INTO usuarios (email, contraseña, rol) VALUES (?, ?, 'estudiante')";
        $stmtUser = $this->conn->prepare($queryUser);
        $stmtUser->bind_param("ss", $data['email'], md5($data['password']));
        
        if ($stmtUser->execute()) {
            $id_usuario = $this->conn->insert_id;
            
            // Luego crear datos del estudiante
            $queryEst = "INSERT INTO estudiantes (id_usuario, nombre, apellidos, fecha_nacimiento, telefono) 
                         VALUES (?, ?, ?, ?, ?)";
            $stmtEst = $this->conn->prepare($queryEst);
            $stmtEst->bind_param("issss", 
                $id_usuario, 
                $data['nombre'], 
                $data['apellidos'], 
                $data['fecha_nacimiento'], 
                $data['telefono']
            );
            
            if ($stmtEst->execute()) {
                return $id_usuario;
            }
        }
        return false;
    }

    public function actualizarEstudiante($id_usuario, $data)
    {
        $query = "UPDATE estudiantes SET nombre = ?, apellidos = ?, fecha_nacimiento = ?, telefono = ? 
                  WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssi", 
            $data['nombre'], 
            $data['apellidos'], 
            $data['fecha_nacimiento'], 
            $data['telefono'],
            $id_usuario
        );
        return $stmt->execute();
    }

    public function eliminarEstudiante($id_usuario)
    {
        $query = "UPDATE usuarios SET activo = 0 WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        return $stmt->execute();
    }

    public function getTotalEstudiantes()
    {
        $query = "SELECT COUNT(*) as total FROM usuarios WHERE rol = 'estudiante' AND activo = 1";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
?>