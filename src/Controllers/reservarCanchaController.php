<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Model/Contacto.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Configuración de errores para desarrollo
// Esto es útil para ver errores de PHP durante el desarrollo, pero no se recomienda en producción
// muestra los errores en el navegador ,si los hay
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

function obtenerReservasSemana($conn, $id_cancha, $dias, $horarios)
{
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reservar') {
    if (!isset($_SESSION['id_usuario'])) {
        echo "Debes estar logueado para reservar.";
        exit;
    }
    if (empty($_POST['cancha'])) {
        echo "Debes seleccionar una cancha.";
        exit;
    }

    $id_usuario = $_SESSION['id_usuario'];
    $id_cancha = intval($_POST['cancha']);
    $fecha = $_POST['fecha'];
    $horario = $_POST['horario'];
    //$precio = floatval($_POST['precio']); // Ahora es el precio real, no un id

    // Obtener o crear id_fecha
    $stmt = $conn->prepare("SELECT id_fecha FROM fecha WHERE fecha = ?");
    $stmt->bind_param("s", $fecha);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $id_fecha = $row['id_fecha'];
    } else {
        // Insertar la fecha si no existe
        $stmt_insert = $conn->prepare("INSERT INTO fecha (fecha) VALUES (?)");
        $stmt_insert->bind_param("s", $fecha);
        if ($stmt_insert->execute()) {
            $id_fecha = $conn->insert_id;
        } else {
            echo "Error al guardar la fecha";
            exit;
        }
    }

    // Obtener id_horario (igual que antes)
    $stmt = $conn->prepare("SELECT id_horario FROM horario WHERE horario = ?");
    $stmt->bind_param("s", $horario);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $id_horario = $row ? $row['id_horario'] : null;

    if ($id_fecha && $id_horario) {
        require_once __DIR__ . '/../Model/Reserva.php';
        $reserva = new Reserva($id_usuario, $id_cancha, $id_fecha, $id_horario);
        if ($reserva->guardar($conn)) {

            // --- Envío de mail de confirmación con PHPMailer ---

            // 1. Obtener email del usuario
            $stmt = $conn->prepare("SELECT email FROM usuario WHERE id_usuario = ?");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $result = $stmt->get_result();
            $rowUser = $result->fetch_assoc();
            $emailUsuario = $rowUser ? $rowUser['email'] : '';

            // 2. Obtener nombre de la cancha
            $stmt = $conn->prepare("SELECT nombreCancha FROM cancha WHERE id_cancha = ?");
            $stmt->bind_param("i", $id_cancha);
            $stmt->execute();
            $result = $stmt->get_result();
            $rowCancha = $result->fetch_assoc();
            $nombreCancha = $rowCancha ? $rowCancha['nombreCancha'] : '';

            // 3. Enviar el mail solo si hay email
            if ($emailUsuario) {
                $mail = new PHPMailer(true);
                try {
                    // Configuración SMTP (ajusta según tu proveedor)
                    $mail->isSMTP();
                    $mail->Host       = $_ENV['MAIL_HOST'];
                    $mail->SMTPAuth   = $_ENV['MAIL_SMTPAuth'] === 'true';
                    $mail->Username   = $_ENV['MAIL_USERNAME'];
                    $mail->Password   = $_ENV['MAIL_PASSWORD'];
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = $_ENV['MAIL_PORT'];

                    $mail->setFrom('no-reply@lacanchitadelospibes.com', 'La Canchita de los Pibes');
                    $mail->addAddress($emailUsuario);

                    $mail->Subject = 'Confirmación de Reserva - La Canchita de los Pibes';
                    $mail->Body = "¡Hola!\n\nTu reserva fue realizada con éxito.\n\n"
                        . "Cancha: $nombreCancha\n"
                        . "Fecha: $fecha\n"
                        . "Horario: $horario\n\n"
                        . "¡Te esperamos!\nLa Canchita de los Pibes";

                    $mail->send();
                    // Opcional: puedes loguear el éxito
                } catch (Exception $e) {
                    // Opcional: puedes loguear el error
                     error_log('Error al enviar mail: ' . $mail->ErrorInfo);
                }
            }
            // --- Fin envío de mail ---

            echo "ok";
        } else {
            echo "Error al guardar la reserva";
        }
    } else {
        echo "Fecha u horario inválido";
    }
    exit;
}
