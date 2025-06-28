<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Model/Contacto.php';
require_once __DIR__ . '/mail_config.php';

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

            // 1. Obtener email y datos del usuario
            $stmt = $conn->prepare("SELECT u.email, p.nombre, p.apellido FROM usuario u 
                                   JOIN persona p ON u.id_persona = p.id_persona 
                                   WHERE u.id_usuario = ?");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $result = $stmt->get_result();
            $rowUser = $result->fetch_assoc();
            $emailUsuario = $rowUser ? $rowUser['email'] : '';
            $nombreCompleto = $rowUser ? $rowUser['nombre'] . ' ' . $rowUser['apellido'] : 'Usuario';

            // 2. Obtener nombre de la cancha
            $stmt = $conn->prepare("SELECT nombreCancha FROM cancha WHERE id_cancha = ?");
            $stmt->bind_param("i", $id_cancha);
            $stmt->execute();
            $result = $stmt->get_result();
            $rowCancha = $result->fetch_assoc();
            $nombreCancha = $rowCancha ? $rowCancha['nombreCancha'] : 'Cancha';

            // 3. Log para debug
            logMail("Intentando enviar correo a: $emailUsuario para reserva de $nombreCancha");

            // 4. Enviar el mail solo si hay email válido
            if ($emailUsuario && filter_var($emailUsuario, FILTER_VALIDATE_EMAIL)) {
                logMail("Email válido, iniciando envío...");
                $mail = new PHPMailer(true);
                try {
                    // Verificar que las variables de entorno existan
                    if (empty($_ENV['MAIL_HOST']) || empty($_ENV['MAIL_USERNAME']) || empty($_ENV['MAIL_PASSWORD'])) {
                        throw new Exception('Configuración de correo incompleta en .env');
                    }
                    
                    logMail("Configuración de correo validada, configurando SMTP...");

                    // Configuración SMTP
                    $mail->isSMTP();
                    $mail->Host       = $_ENV['MAIL_HOST'];
                    $mail->SMTPAuth   = $_ENV['MAIL_SMTPAuth'] === 'true';
                    $mail->Username   = $_ENV['MAIL_USERNAME'];
                    $mail->Password   = $_ENV['MAIL_PASSWORD'];
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = intval($_ENV['MAIL_PORT']);
                    
                    // Configuración adicional para Gmail
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );

                    $mail->setFrom($_ENV['MAIL_USERNAME'], 'La Canchita de los Pibes');
                    $mail->addAddress($emailUsuario, $nombreCompleto);

                    $mail->isHTML(true);
                    $mail->Subject = 'Confirmación de Reserva - La Canchita de los Pibes';
                    $mail->Body = "
                        <h3>¡Hola $nombreCompleto!</h3>
                        <p>Tu reserva fue realizada con éxito.</p>
                        <div style='background-color: #f8f9fa; padding: 15px; border-left: 4px solid #28a745; margin: 15px 0;'>
                            <h4>Detalles de tu reserva:</h4>
                            <ul>
                                <li><strong>Cancha:</strong> $nombreCancha</li>
                                <li><strong>Fecha:</strong> " . date('d/m/Y', strtotime($fecha)) . "</li>
                                <li><strong>Horario:</strong> " . date('H:i', strtotime($horario)) . " hs</li>
                            </ul>
                        </div>
                        <p>¡Te esperamos!<br><strong>La Canchita de los Pibes</strong></p>
                        <hr>
                        <small><em>Si tienes alguna consulta, no dudes en contactarnos.</em></small>
                    ";

                    // Versión texto plano como alternativa
                    $mail->AltBody = "¡Hola $nombreCompleto!\n\n"
                        . "Tu reserva fue realizada con éxito.\n\n"
                        . "Detalles de tu reserva:\n"
                        . "- Cancha: $nombreCancha\n"
                        . "- Fecha: " . date('d/m/Y', strtotime($fecha)) . "\n"
                        . "- Horario: " . date('H:i', strtotime($horario)) . " hs\n\n"
                        . "¡Te esperamos!\nLa Canchita de los Pibes";

                    logMail("Enviando correo...");
                    $mail->send();
                    logMail('Correo de confirmación enviado exitosamente a: ' . $emailUsuario);
                    
                } catch (Exception $e) {
                    logMail('Error al enviar mail de confirmación: ' . $e->getMessage());
                    logMail('PHPMailer ErrorInfo: ' . $mail->ErrorInfo);
                    // No detener el proceso si falla el envío del correo
                }
            } else {
                logMail('No se pudo enviar correo: email vacío o inválido (' . $emailUsuario . ')');
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
