<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define la ruta BASE_URL //
if (!defined('BASE_URL')) {
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    //$carpeta = '/Mis_Proyectos/IFTS12-LaCanchitaDeLosPibes';// XAMPP
    $carpeta = ''; // SIN subcarpeta// POR PHP - s LOCALHOST:8000
    //$carpeta = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    define('BASE_URL', $protocolo . $host . $carpeta);
// Inicia la sesión antes de cualquier salida
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Model/Usuario.php';
require_once __DIR__ . '/../Model/Empleado.php';
require_once __DIR__ . '/../ConectionBD/CConection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //   $email = $_POST['email'] ?? '';
    //   $clave = $_POST['clave'] ?? '';
    $email = trim($_POST['email'] ?? null);
    $clave = trim($_POST['clave'] ?? null);
    $conn = (new ConectionDB())->getConnection();

    // Buscar usuario por email
    $stmt = $conn->prepare("SELECT u.email, u.clave, u.id_usuario, u.id_persona, e.id_rol, r.rol
        FROM usuario u
        JOIN empleado e ON u.id_usuario = e.id_usuario
        JOIN roles r ON e.id_rol = r.id_roles
        WHERE u.email = ? AND u.habilitado = 1 AND u.cancelado = 0");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        $usuarioObj = new Usuario($fila['email'], $fila['clave'], $fila['id_persona'], $fila['id_usuario'], false);

        // Verificar la clave usando el método de la clase
        if ($usuarioObj->verificarClave($clave)) {
            // Login correcto: guardar datos en sesión
            $_SESSION['email'] = $fila['email'];
            $_SESSION['logged_in'] = true;
            $_SESSION['id_usuario'] = $fila['id_usuario'];
            $_SESSION['id_rol'] = $fila['id_rol'];
            $_SESSION['nombre_rol'] = $fila['rol'];

            header("Location: " . BASE_URL . "/index.php");
            exit;
        }
    }

        // Si llegó aquí, login incorrecto
        $_SESSION['error_message'] = "Usuario o contraseña incorrectos.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        header("Location: ../../index.php");
        exit;
    }
}
