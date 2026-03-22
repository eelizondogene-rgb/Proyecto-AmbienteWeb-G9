<?php
class Database
{
    private $host = "db";
    private $db = "appdb";
    private $user = "appuser";
    private $pass = "apppass";
    private $conn;

    public function connect()
    {
        try {
            $this->conn = new mysqli(
                $this->host,
                $this->user,
                $this->pass,
                $this->db
            );

            if ($this->conn->connect_error) {
                throw new Exception("Error conexión: " . $this->conn->connect_error);
            }

            // Establecer charset UTF-8
            $this->conn->set_charset("utf8");

            return $this->conn;
        } catch (Exception $e) {
            die("Error de base de datos: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function prepare($sql)
    {
        return $this->conn->prepare($sql);
    }

    public function query($sql)
    {
        return $this->conn->query($sql);
    }

    public function escapeString($str)
    {
        return $this->conn->real_escape_string($str);
    }

    public function lastInsertId()
    {
        return $this->conn->insert_id;
    }
}
?>