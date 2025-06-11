<?php
class Persona {
    private $id;
    private $nombre;
    private $apellido;
    private $dni;
    private $telefono;

    public function __construct($nombre, $apellido, $dni, $telefono, $id = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->dni = $dni;
        $this->telefono = $telefono;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getApellido() { return $this->apellido; }
    public function getDni() { return $this->dni; }
    public function getTelefono() { return $this->telefono; }

    // Setters
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellido($apellido) { $this->apellido = $apellido; }
    public function setDni($dni) { $this->dni = $dni; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }

    // Guardar persona en la base de datos
    public function guardar($conn) {
        $stmt = $conn->prepare("INSERT INTO persona (nombre, apellido, dni, telefono) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $this->nombre, $this->apellido, $this->dni, $this->telefono);
        if ($stmt->execute()) {
            $this->id = $conn->insert_id;
            return true;
        }
        return false;
    }

    // Buscar persona por ID
    public static function buscarPorId($conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM persona WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($fila = $resultado->fetch_assoc()) {
            return new Persona($fila['nombre'], $fila['apellido'], $fila['dni'], $fila['telefono'], $fila['id']);
        }
        return null;
    }

    public function actualizar($conn) {
        $stmt = $conn->prepare("UPDATE persona SET nombre = ?, apellido = ?, dni = ?, telefono = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $this->nombre, $this->apellido, $this->dni, $this->telefono, $this->id);
        return $stmt->execute();
    }

    public function eliminar($conn) {
        $stmt = $conn->prepare("DELETE FROM persona WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
}