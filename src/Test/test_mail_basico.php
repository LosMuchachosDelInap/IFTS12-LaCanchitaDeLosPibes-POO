<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

// Configuración de errores para desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

echo "=== PRUEBA DE CONFIGURACIÓN DE CORREO ===<br><br>";

// Verificar variables de entorno
echo "<strong>Variables de entorno:</strong><br>";
echo "MAIL_HOST: " . ($_ENV['MAIL_HOST'] ?? 'NO DEFINIDO') . "<br>";
echo "MAIL_PORT: " . ($_ENV['MAIL_PORT'] ?? 'NO DEFINIDO') . "<br>";
echo "MAIL_USERNAME: " . ($_ENV['MAIL_USERNAME'] ?? 'NO DEFINIDO') . "<br>";
echo "MAIL_PASSWORD: " . (empty($_ENV['MAIL_PASSWORD']) ? 'NO DEFINIDO' : 'DEFINIDO') . "<br>";
echo "MAIL_SMTPAuth: " . ($_ENV['MAIL_SMTPAuth'] ?? 'NO DEFINIDO') . "<br><br>";

// Intentar enviar un correo de prueba
$mail = new PHPMailer(true);

try {
    echo "<strong>Intentando enviar correo de prueba...</strong><br>";
    
    // Configuración SMTP
    $mail->isSMTP();
    $mail->Host       = $_ENV['MAIL_HOST'];
    $mail->SMTPAuth   = $_ENV['MAIL_SMTPAuth'] === 'true';
    $mail->Username   = $_ENV['MAIL_USERNAME'];
    $mail->Password   = $_ENV['MAIL_PASSWORD'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $_ENV['MAIL_PORT'];

    // Habilitar debug para ver más información
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = 'html';

    $mail->setFrom($_ENV['MAIL_USERNAME'], 'La Canchita de los Pibes - PRUEBA');
    $mail->addAddress($_ENV['MAIL_USERNAME']); // Enviar a nosotros mismos

    $mail->isHTML(true);
    $mail->Subject = 'Prueba de configuración PHPMailer';
    $mail->Body = '<h3>¡Prueba exitosa!</h3><p>Si recibes este correo, la configuración de PHPMailer está funcionando correctamente.</p>';

    $mail->send();
    echo '<br><div style="color: green; font-weight: bold;">¡Correo enviado exitosamente!</div>';
    
} catch (Exception $e) {
    echo '<br><div style="color: red; font-weight: bold;">Error al enviar el correo:</div>';
    echo '<div style="color: red;">' . $mail->ErrorInfo . '</div>';
    echo '<div style="color: red;">Excepción: ' . $e->getMessage() . '</div>';
}

?>
