<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Model/Contacto.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    // Si falta email o mensaje, mostrar error y no enviar
    if (empty($email) || empty($mensaje)) {
        echo "<div class='alert alert-danger'>Debe completar todos los campos.</div>";
        exit;
    }

    $contacto = new Contacto($email, $mensaje);

    $mail = new PHPMailer(true);
    try {
        // Configuración básica de PHPMailer
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = $_ENV['MAIL_SMTPAuth'] === 'true';
        $mail->Username   = $_ENV['MAIL_USERNAME'];
        $mail->Password   = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $_ENV['MAIL_PORT'];

        $mail->setFrom($contacto->getEmail(), 'Consulta Web');
        $mail->addAddress($_ENV['MAIL_USERNAME']); // Destinatario

        $mail->isHTML(true);
        $mail->Subject = 'Nueva consulta desde el sitio';
        $mail->Body = "
            <b>Usuario:</b> " . htmlspecialchars($contacto->getEmail()) . "<br>
            <b>Mensaje:</b><br>" . nl2br(htmlspecialchars($contacto->getMensaje()));

        $mail->send();
        echo "<div class='alert alert-success'>¡Consulta enviada correctamente!</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>No se pudo enviar el mensaje. Error: {$mail->ErrorInfo}</div>";
    }
    exit;
}
