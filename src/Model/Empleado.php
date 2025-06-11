<?php
class Empleado {
    private $id;
    private $id_rol;
    private $id_persona;
    private $id_usuario;

    public function __construct($id_rol, $id_persona, $id_usuario, $id = null) {
        $this->id = $id;
        $this->id_rol = $id_rol;
        $this->id_persona = $id_persona;
        $this->id_usuario = $id_usuario;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getIdRol() { return $this->id_rol; }
    public function getIdPersona() { return $this->id_persona; }
    public function getIdUsuario() { return $this->id_usuario; }

    // Setters
    public function setIdRol($id_rol) { $this->id_rol = $id_rol; }
    public function setIdPersona($id_persona) { $this->id_persona = $id_persona; }
    public function setIdUsuario($id_usuario) { $this->id_usuario = $id_usuario; }

    // Guardar empleado en la base de datos
    public function guardar($conn) {
        $stmt = $conn->prepare("INSERT INTO empleado (id_rol, id_persona, id_usuario) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $this->id_rol, $this->id_persona, $this->id_usuario);
        if ($stmt->execute()) {
            $this->id = $conn->insert_id;
            return true;
        }
        return false;
    }

    // Buscar empleado por ID
    public static function buscarPorId($conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM empleado WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($fila = $resultado->fetch_assoc()) {
            return new Empleado($fila['id_rol'], $fila['id_persona'], $fila['id_usuario'], $fila['id']);
        }
        return null;
    }

    public function actualizar($conn) {
        $stmt = $conn->prepare("UPDATE empleado SET id_rol = ?, id_persona = ?, id_usuario = ? WHERE id = ?");
        $stmt->bind_param("iiii", $this->id_rol, $this->id_persona, $this->id_usuario, $this->id);
        return $stmt->execute();
    }

    public function eliminar($conn) {
        $stmt = $conn->prepare("UPDATE empleado SET habilitado = 0, cancelado = 1 WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
}