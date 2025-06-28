<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Configuración de errores para desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Llamo al archivo de la clase de conexión
require_once __DIR__ . '/../ConectionBD/CConection.php';

// Instancio la clase
$conectarDB = new ConectionDB();
// Obtengo la conexión
$conn = $conectarDB->getConnection();

echo "=== SIMULACIÓN DE PROCESO DE RESERVA ===<br><br>";

// Simular datos de sesión (reemplaza con un ID de usuario real de tu BD)
$_SESSION['id_usuario'] = 1; // Cambia este ID por uno que exista en tu BD

// Datos de prueba para la reserva
$id_usuario = $_SESSION['id_usuario'];
$id_cancha = 1; // Cambia por un ID de cancha que exista
$fecha = date('Y-m-d', strtotime('+1 day')); // Mañana
$horario = '14:00:00';

echo "<strong>Datos de prueba:</strong><br>";
echo "ID Usuario: $id_usuario<br>";
echo "ID Cancha: $id_cancha<br>";
echo "Fecha: $fecha<br>";
echo "Horario: $horario<br><br>";

// 1. Verificar que el usuario existe y obtener su email
echo "<strong>1. Verificando usuario...</strong><br>";
$stmt = $conn->prepare("SELECT email, id_persona FROM usuario WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$rowUser = $result->fetch_assoc();

if ($rowUser) {
    $emailUsuario = $rowUser['email'];
    echo "✓ Usuario encontrado: $emailUsuario<br>";
    
    // Obtener datos de la persona
    $stmt2 = $conn->prepare("SELECT nombre, apellido FROM persona WHERE id_persona = ?");
    $stmt2->bind_param("i", $rowUser['id_persona']);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $rowPersona = $result2->fetch_assoc();
    $nombreCompleto = $rowPersona ? $rowPersona['nombre'] . ' ' . $rowPersona['apellido'] : 'Usuario';
    echo "✓ Nombre: $nombreCompleto<br>";
} else {
    echo "✗ Usuario no encontrado<br>";
    exit;
}

// 2. Verificar que la cancha existe
echo "<br><strong>2. Verificando cancha...</strong><br>";
$stmt = $conn->prepare("SELECT nombreCancha FROM cancha WHERE id_cancha = ?");
$stmt->bind_param("i", $id_cancha);
$stmt->execute();
$result = $stmt->get_result();
$rowCancha = $result->fetch_assoc();

if ($rowCancha) {
    $nombreCancha = $rowCancha['nombreCancha'];
    echo "✓ Cancha encontrada: $nombreCancha<br>";
} else {
    echo "✗ Cancha no encontrada<br>";
    exit;
}

// 3. Obtener o crear fecha
echo "<br><strong>3. Procesando fecha...</strong><br>";
$stmt = $conn->prepare("SELECT id_fecha FROM fecha WHERE fecha = ?");
$stmt->bind_param("s", $fecha);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    $id_fecha = $row['id_fecha'];
    echo "✓ Fecha existente encontrada (ID: $id_fecha)<br>";
} else {
    // Insertar la fecha si no existe
    $stmt_insert = $conn->prepare("INSERT INTO fecha (fecha) VALUES (?)");
    $stmt_insert->bind_param("s", $fecha);
    if ($stmt_insert->execute()) {
        $id_fecha = $conn->insert_id;
        echo "✓ Nueva fecha creada (ID: $id_fecha)<br>";
    } else {
        echo "✗ Error al crear la fecha<br>";
        exit;
    }
}

// 4. Obtener horario
echo "<br><strong>4. Procesando horario...</strong><br>";
$stmt = $conn->prepare("SELECT id_horario FROM horario WHERE horario = ?");
$stmt->bind_param("s", $horario);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    $id_horario = $row['id_horario'];
    echo "✓ Horario encontrado (ID: $id_horario)<br>";
} else {
    echo "✗ Horario no encontrado en la BD<br>";
    exit;
}

// 5. Intentar crear la reserva (sin guardarla realmente)
echo "<br><strong>5. Simulando creación de reserva...</strong><br>";
echo "✓ Todos los datos están listos para crear la reserva<br>";

// 6. Probar envío de correo
echo "<br><strong>6. Probando envío de correo...</strong><br>";

if ($emailUsuario) {
    $mail = new PHPMailer(true);
    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = $_ENV['MAIL_SMTPAuth'] === 'true';
        $mail->Username   = $_ENV['MAIL_USERNAME'];
        $mail->Password   = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $_ENV['MAIL_PORT'];

        // Configuración adicional para Gmail
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Debug para ver más información
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';

        $mail->setFrom($_ENV['MAIL_USERNAME'], 'La Canchita de los Pibes');
        $mail->addAddress($emailUsuario, $nombreCompleto);

        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de Reserva - La Canchita de los Pibes [PRUEBA]';
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
            <small><em>Este es un correo de prueba del sistema.</em></small>
        ";

        $mail->send();
        echo '<br><div style="color: green; font-weight: bold;">✓ ¡Correo enviado exitosamente!</div>';
        
    } catch (Exception $e) {
        echo '<br><div style="color: red; font-weight: bold;">✗ Error al enviar el correo:</div>';
        echo '<div style="color: red;">Error Info: ' . $mail->ErrorInfo . '</div>';
        echo '<div style="color: red;">Excepción: ' . $e->getMessage() . '</div>';
    }
} else {
    echo "✗ No hay email del usuario<br>";
}

echo "<br><br><strong>=== FIN DE LA PRUEBA ===</strong>";

?>
