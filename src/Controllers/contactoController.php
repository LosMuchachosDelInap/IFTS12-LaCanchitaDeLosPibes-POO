<?php
// filepath: c:\xampp\htdocs\Mis proyectos\IFTS12-LaCanchitaDeLosPibes\src\Controllers\ContactoController.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

// Cargar variables de entorno solo una vez
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
error_log('DEBUG ENV: ' . json_encode($_ENV));

class ContactoController
{
    private $emailUsuario;
    private $mensaje;
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
    }

    public function setEmailUsuario($email)
    {
        $this->emailUsuario = $email;
    }

    public function getEmailUsuario()
    {
        return $this->emailUsuario;
    }

    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;
    }

    public function getMensaje()
    {
        return $this->mensaje;
    }

    public function enviarConsulta()
    {
        if (empty($this->emailUsuario) || empty($this->mensaje)) {
            return "<div class='alert alert-danger'>Debe completar todos los campos.</div>";
        }

        try {
            // Configuración del servidor SMTP usando variables de entorno
            $this->mail->isSMTP();
            $this->mail->Host       = $_ENV['MAIL_HOST'];
            $this->mail->SMTPAuth   = $_ENV['MAIL_SMTPAuth'] === 'true';
            $this->mail->Username   = $_ENV['MAIL_USERNAME'];
            $this->mail->Password   = $_ENV['MAIL_PASSWORD'];
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port       = $_ENV['MAIL_PORT'];

            // Remitente y destinatario
            error_log('MAIL_USERNAME: ' . getenv('MAIL_USERNAME'));// esto es para ver el errror
            $this->mail->setFrom($_ENV['MAIL_USERNAME'], 'Consulta Web');
            $this->mail->addAddress($_ENV['MAIL_USERNAME'], 'Contacto La Canchita de los Pibes');
            $this->mail->addReplyTo($this->emailUsuario, 'Consulta Web');
            $this->mail->CharSet = 'UTF-8';
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Nueva consulta desde el formulario de contacto';
            $this->mail->Body    = "<p><b>Usuario:</b> {$this->emailUsuario}</p>
                                    <p><b>Consulta:</b><br>{$this->mensaje}</p>";

            $this->mail->send();
            return "<div class='alert alert-success'>¡Consulta enviada correctamente!, te estaremos contactando a la brevedad.</div>";
        } catch (Exception $e) {
            return "<div class='alert alert-danger'>Error al enviar la consulta: {$this->mail->ErrorInfo}</div>";
        }
    }
}