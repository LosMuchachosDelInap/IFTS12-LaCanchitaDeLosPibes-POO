<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Model/Persona.php';
require_once __DIR__ . '/../Model/Usuario.php';
require_once __DIR__ . '/../Model/Empleado.php';
require_once __DIR__ . '/../ConectionBD/CConection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $dni      = $_POST['dni'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $email    = $_POST['email'] ?? '';
    $clave    = $_POST['clave'] ?? '';
    $rol      = $_POST['rol'] ?? 6;

    $conn = (new ConectionDB())->getConnection();

    // Validar que el email no exista
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['registro_message'] = "El email ya está registrado.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Crear persona
    $persona = new Persona($nombre, $apellido, $dni, $telefono);
    if (!$persona->guardar($conn)) {
        $_SESSION['registro_message'] = "Error al registrar persona.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Crear usuario
    $usuario = new Usuario($email, $clave, $persona->getId());
    if (!$usuario->guardar($conn)) {
        $_SESSION['registro_message'] = "Error al registrar usuario.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Crear empleado
    $empleado = new Empleado($rol, $persona->getId(), $usuario->getId());
    if (!$empleado->guardar($conn)) {
        $_SESSION['registro_message'] = "Error al registrar empleado.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $_SESSION['registro_message'] = "¡Registro exitoso! Ya puedes ingresar.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    header("Location: ../../index.php");
    exit;
}
