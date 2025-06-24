<?php



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!defined('BASE_URL')) {
  $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
  $host = $_SERVER['HTTP_HOST'];
  // $carpeta = '/Mis_proyectos/IFTS12-LaCanchitaDeLosPibes';// cuando usas XAMPP
  $carpeta = ''; // cuando usas <localhost:8000>
  define('BASE_URL', $protocolo . $host . $carpeta);
}

// Inicia la sesión antes de cualquier salida
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
// Llamo al archivo de la clase de conexión (lo requiero para poder instanciar la clase)
require_once __DIR__ . '/../ConectionBD/CConection.php';
// Instancio la clase
$conectarDB = new ConectionDB();
// Obtengo la conexión
$conn = $conectarDB->getConnection();

function obtenerReservasSemana($conn, $id_cancha, $dias, $horarios) {
      if (empty($dias) || empty($horarios)) {
        return [];
    }
    $reservas = [];
    $ids_fechas = implode(',', array_map('intval', $dias));
    $ids_horarios = implode(',', array_map('intval', $horarios));
    $sql = "SELECT id_fecha, id_horario FROM reserva WHERE id_cancha = ? AND id_fecha IN ($ids_fechas) AND id_horario IN ($ids_horarios)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cancha);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $reservas[$row['id_fecha']][$row['id_horario']] = true;
    }
    return $reservas;
}