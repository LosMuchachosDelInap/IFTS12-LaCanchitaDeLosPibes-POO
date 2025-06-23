<?php
session_start();
require_once __DIR__ . '/../Model/Empleado.php';
require_once __DIR__ . '/../Model/Persona.php';
require_once __DIR__ . '/../Model/Usuario.php';
require_once __DIR__ . '/../ConectionBD/CConection.php';

$conn = (new ConectionDB())->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar'])) {
    $idPersona = $_POST['id_persona'];
    $idUsuario = $_POST['id_usuario'];
    $idEmpleado = $_POST['id_empleado'];
    $idRol = $_POST['rol'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $edad = $_POST['edad'];
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];
    $email = $_POST['usuario'];
    $clave = $_POST['clave'];

    // Actualizar persona
    $persona = Persona::buscarPorId($conn, $idPersona);
    if ($persona) {
        $persona->setNombre($nombre);
        $persona->setApellido($apellido);
         $persona->setEdad($edad);
        $persona->setDni($dni);
        $persona->setTelefono($telefono);
        // Si tienes edad, agrega setEdad()
        $persona->actualizar($conn); 
    }

    // Actualizar usuario
    $usuario = Usuario::buscarPorId($conn, $idUsuario);
    if ($usuario) {
        $usuario->setEmail($email);
        if (!empty($clave)) {
            $usuario->setClave($clave); // Hashea internamente
        }
        $usuario->actualizar($conn); 
    }

    // Actualizar empleado
    $empleado = Empleado::buscarPorId($conn, $idEmpleado);
    if ($empleado) {
        $empleado->setIdRol($idRol);
        $empleado->actualizar($conn); 
    }

    header("Location: ../Views/listado.php");
    exit;
}