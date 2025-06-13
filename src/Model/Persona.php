<?php
class Persona
{
    private $id_persona;
    private $nombre;
    private $apellido;
    private $edad;
    private $dni;
    private $telefono;

    public function __construct($nombre, $apellido, $edad, $dni, $telefono, $id_persona = null)
    {
        $this->id_persona = $id_persona;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->edad = $edad;
        $this->dni = $dni;
        $this->telefono = $telefono;
    }

    // Getters
    public function getId()
    {
        return $this->id_persona;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getApellido()
    {
        return $this->apellido;
    }
     public function getEdad()
    {
        return $this->edad;
    }
    public function getDni()
    {
        return $this->dni;
    }
    public function getTelefono()
    {
        return $this->telefono;
    }

    // Setters
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }
      public function setEdad($edad)
    {
        $this->dni = $edad;
    }
    public function setDni($dni)
    {
        $this->dni = $dni;
    }
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    // Guardar persona en la base de datos
    public function guardar($conn)
    {
        $stmt = $conn->prepare("INSERT INTO persona (nombre, apellido, edad, dni, telefono) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $this->nombre, $this->apellido, $this->dni, $this->edad, $this->telefono);
        if ($stmt->execute()) {
            $this->id_persona = $conn->insert_id;
            return true;
        }
        return false;
    }

    // Buscar persona por ID
    public static function buscarPorId($conn, $id)
    {
        $stmt = $conn->prepare("SELECT * FROM persona WHERE id_persona = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($fila = $resultado->fetch_assoc()) {
            return new Persona($fila['nombre'], $fila['apellido'], $fila['dni'], $fila['edad'],$fila['telefono'], $fila['id_persona']);
        }
        return null;
    }

    public function actualizar($conn)
    {
        $stmt = $conn->prepare("UPDATE persona SET nombre = ?, apellido = ?, dni = ?, edad = ?, telefono = ? WHERE id_persona = ?");
        $stmt->bind_param("ssssi", $this->nombre, $this->apellido, $this->dni, $this->edad, $this->telefono, $this->id_persona);
        return $stmt->execute();
    }

    public function eliminar($conn)
    {
        $stmt = $conn->prepare("DELETE FROM persona WHERE id_persona = ?");
        $stmt->bind_param("i", $this->id_persona);
        return $stmt->execute();
    }
}
