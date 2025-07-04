<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definir BASE_URL si no está definida
if (!defined('BASE_URL')) {
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $carpeta = ''; // Ajusta si usas subcarpeta
    define('BASE_URL', $protocolo . $host . $carpeta);
}

// Verifica si la sesión ya ha sido iniciada
if (session_status() === PHP_SESSION_NONE) { 
    session_start();
}

require_once __DIR__ . '/../ConectionBD/CConection.php';
require_once __DIR__ . '/../Model/peticionesSql.php';

class EliminarEmpleado {
    private $conn;

    // Constructor opcionalmente puede recibir la conexión
    public function __construct($conn = null) {
        if ($conn !== null) {
            $this->setConn($conn);
        }
    }

    // Setter para la conexión
    public function setConn($conn) {
        $this->conn = $conn;
    }

    // Getter para la conexión
    public function getConn() {
        return $this->conn;
    }

    /**
     * Deshabilita (no elimina físicamente) un empleado por su ID.
     * @param int $idEmpleado
     * @return bool
     */
    public function deshabilitarPorId($idEmpleado) {
        global $eliminarEmpleado; // Trae la variable desde peticionesSql.php
        $stmt = $this->getConn()->prepare($eliminarEmpleado);
        if ($stmt) {
            $stmt->bind_param("i", $idEmpleado);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }
}

// Instancio la clase y obtengo la conexión
$conectarDB = new ConectionDB();
$conn = $conectarDB->getConnection();

$eliminador = new EliminarEmpleado();
$eliminador->setConn($conn);

$idEmpleado = $_GET['id_empleado'] ?? $_POST['id_empleado'] ?? null;

if ($idEmpleado) {
    if ($eliminador->deshabilitarPorId($idEmpleado)) {
        $_SESSION['mensaje'] = 'Empleado eliminado con éxito';
    } else {
        $_SESSION['mensaje'] = 'Error al eliminar empleado';
    }
    header('Location: ' . BASE_URL . '/src/Views/listado.php');
    exit;
} else {
    $_SESSION['mensaje'] = 'ID de empleado no especificado';
    header('Location: ' . BASE_URL . '/src/Views/listado.php');
    exit;
}
?>
