<?php

class CodigoAcceso
{
    private $conn;
    private $table = "codigos_acceso";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT c.*, e.nombre as examen_nombre 
                  FROM " . $this->table . " c 
                  LEFT JOIN examenes e ON c.id_examen = e.id_examen 
                  ORDER BY c.created_at DESC";
        $result = $this->conn->query($query);
        $codigos = [];
        
        while ($row = $result->fetch_assoc()) {
            $codigos[] = $row;
        }
        return $codigos;
    }

    public function getAllWithAsignacion()
    {
        $query = "SELECT c.*, e.nombre as examen_nombre, 
                  (SELECT COUNT(*) FROM asignacion_codigo WHERE id_codigo = c.id_codigo) as asignado
                  FROM " . $this->table . " c 
                  LEFT JOIN examenes e ON c.id_examen = e.id_examen 
                  ORDER BY c.created_at DESC";
        $result = $this->conn->query($query);
        $codigos = [];
        
        while ($row = $result->fetch_assoc()) {
            $codigos[] = $row;
        }
        return $codigos;
    }

    public function getByExamen($id_examen)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_examen = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_examen);
        $stmt->execute();
        $result = $stmt->get_result();
        $codigos = [];
        
        while ($row = $result->fetch_assoc()) {
            $codigos[] = $row;
        }
        return $codigos;
    }

    public function getTotal()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function diagnosticarCodigo($codigo)
    {
        $query = "SELECT c.*, e.estado as examen_estado, e.nombre as examen_nombre,
                  CASE 
                      WHEN c.estado != 'disponible' THEN 'Código no disponible'
                      WHEN e.estado != 'activo' THEN 'Examen no activo'
                      WHEN c.fecha_vencimiento IS NOT NULL AND c.fecha_vencimiento < NOW() THEN 'Código expirado'
                      WHEN c.usos_actuales >= c.usos_max THEN 'Sin usos disponibles'
                      ELSE 'VALIDO'
                  END as razon
                  FROM " . $this->table . " c 
                  JOIN examenes e ON c.id_examen = e.id_examen 
                  WHERE c.codigo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function validarCodigo($codigo)
    {
        $query = "SELECT c.*, e.* 
                  FROM " . $this->table . " c 
                  JOIN examenes e ON c.id_examen = e.id_examen 
                  WHERE c.codigo = ? 
                  AND c.estado = 'disponible' 
                  AND e.estado = 'activo'
                  AND (c.fecha_vencimiento IS NULL OR c.fecha_vencimiento > NOW())
                  AND c.usos_actuales < c.usos_max
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function getByCodigo($codigo)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE codigo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function generarCodigo($id_examen, $usos_max = 1, $fecha_vencimiento = null)
    {
        $codigo = strtoupper(substr(md5(uniqid()), 0, 10));
        
        if ($fecha_vencimiento === '') {
            $fecha_vencimiento = null;
        }
        
        $query = "INSERT INTO " . $this->table . " (codigo, id_examen, usos_max, fecha_vencimiento) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("siis", $codigo, $id_examen, $usos_max, $fecha_vencimiento);
        
        if ($stmt->execute()) {
            return $codigo;
        }
        return false;
    }

    public function generarMultiples($id_examen, $cantidad, $usos_max = 1, $fecha_vencimiento = null)
    {
        if ($fecha_vencimiento === '') {
            $fecha_vencimiento = null;
        }
        
        $codigos = [];
        for ($i = 0; $i < $cantidad; $i++) {
            $codigo = $this->generarCodigo($id_examen, $usos_max, $fecha_vencimiento);
            if ($codigo) {
                $codigos[] = $codigo;
            }
        }
        return $codigos;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_codigo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function eliminar($id)
{
    $queryGetSesiones = "SELECT id_sesion FROM sesiones_examen WHERE id_codigo = ?";
    $stmtGet = $this->conn->prepare($queryGetSesiones);
    $stmtGet->bind_param("i", $id);
    $stmtGet->execute();
    $resultSesiones = $stmtGet->get_result();
    
    while ($sesion = $resultSesiones->fetch_assoc()) {
     
        $queryRespuestas = "DELETE FROM respuestas_estudiante WHERE id_sesion = ?";
        $stmtRespuestas = $this->conn->prepare($queryRespuestas);
        $stmtRespuestas->bind_param("i", $sesion['id_sesion']);
        $stmtRespuestas->execute();
        
        $queryResultados = "DELETE FROM resultados_examen WHERE id_sesion = ?";
        $stmtResultados = $this->conn->prepare($queryResultados);
        $stmtResultados->bind_param("i", $sesion['id_sesion']);
        $stmtResultados->execute();
    }
    
    $querySesion = "DELETE FROM sesiones_examen WHERE id_codigo = ?";
    $stmtSesion = $this->conn->prepare($querySesion);
    $stmtSesion->bind_param("i", $id);
    $stmtSesion->execute();
    
    $queryAsignacion = "DELETE FROM asignacion_codigo WHERE id_codigo = ?";
    $stmtAsignacion = $this->conn->prepare($queryAsignacion);
    $stmtAsignacion->bind_param("i", $id);
    $stmtAsignacion->execute();
    
    $query = "DELETE FROM " . $this->table . " WHERE id_codigo = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

    public function revocar($id)
    {
        $query = "UPDATE " . $this->table . " SET estado = 'revocado' WHERE id_codigo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function incrementarUso($id)
    {
        $query = "UPDATE " . $this->table . " 
                  SET usos_actuales = usos_actuales + 1,
                      estado = CASE WHEN usos_actuales + 1 >= usos_max THEN 'usado' ELSE estado END
                  WHERE id_codigo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>