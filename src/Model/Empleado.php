<?php
class Empleado {
    private $id_empleado;
    private $id_rol;
    private $id_persona;
    private $id_usuario;

    public function __construct($id_rol, $id_persona, $id_usuario, $id_empleado = null) {
        $this->id_empleado = $id_empleado;
        $this->id_rol = $id_rol;
        $this->id_persona = $id_persona;
        $this->id_usuario = $id_usuario;
    }

    // Getters
    public function getIdEmpleado() { return $this->id_empleado; }
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
            $this->id_empleado = $conn->insert_id;
            return true;
        }
        return false;
    }

    // Buscar empleado por ID
    public static function buscarPorId($conn, $id_empleado) {
        $stmt = $conn->prepare("SELECT * FROM empleado WHERE id_empleado = ?");
        $stmt->bind_param("i", $id_empleado);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($fila = $resultado->fetch_assoc()) {
            return new Empleado($fila['id_rol'], $fila['id_persona'], $fila['id_usuario'], $fila['id_empleado']);
        }
        return null;
    }

    public function actualizar($conn) {
        $stmt = $conn->prepare("UPDATE empleado SET id_rol = ?, id_persona = ?, id_usuario = ? WHERE id_empleado = ?");
        $stmt->bind_param("iiii", $this->id_rol, $this->id_persona, $this->id_usuario, $this->id_empleado);
        return $stmt->execute();
    }

    public function eliminar($conn) {
        $stmt = $conn->prepare("UPDATE empleado SET habilitado = 0, cancelado = 1 WHERE id_empleado = ?");
        $stmt->bind_param("i", $this->id_empleado);
        return $stmt->execute();
    }

     public static function listarTodos($conn) {
        $sql = "SELECT e.id_empleado, u.email, p.nombre, p.apellido, p.edad, p.dni, p.telefono, r.rol
                FROM empleado e
                JOIN usuario u ON e.id_usuario = u.id_usuario
                JOIN persona p ON e.id_persona = p.id_persona
                JOIN roles r ON e.id_rol = r.id_roles
                ORDER BY e.id_empleado DESC";
        $result = $conn->query($sql);
        $empleados = [];
        while ($row = $result->fetch_assoc()) {
            $empleados[] = $row;
        }
        return $empleados;
    }
}