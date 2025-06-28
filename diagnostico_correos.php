<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico Sistema de Correos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        .info { color: blue; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 3px; overflow-x: auto; }
    </style>
</head>
<body>

<h1>🔍 Diagnóstico Sistema de Correos - La Canchita de los Pibes</h1>

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/ConectionBD/CConection.php';

use Dotenv\Dotenv;

// Configuración de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "<div class='section'>";
echo "<h2>1. ✅ Verificación de Variables de Entorno</h2>";

$envVars = ['MAIL_HOST', 'MAIL_PORT', 'MAIL_USERNAME', 'MAIL_PASSWORD', 'MAIL_SMTPAuth'];
$allEnvOk = true;

foreach ($envVars as $var) {
    $value = $_ENV[$var] ?? null;
    if ($value) {
        if ($var === 'MAIL_PASSWORD') {
            echo "<span class='success'>✓ $var: CONFIGURADO</span><br>";
        } else {
            echo "<span class='success'>✓ $var: $value</span><br>";
        }
    } else {
        echo "<span class='error'>✗ $var: NO CONFIGURADO</span><br>";
        $allEnvOk = false;
    }
}

if (!$allEnvOk) {
    echo "<div class='error'>⚠️ Hay variables de entorno faltantes. Revisar archivo .env</div>";
}
echo "</div>";

// Conectar a la base de datos
echo "<div class='section'>";
echo "<h2>2. 🗄️ Verificación de Base de Datos</h2>";

try {
    $conectarDB = new ConectionDB();
    $conn = $conectarDB->getConnection();
    echo "<span class='success'>✓ Conexión a base de datos exitosa</span><br>";
    
    // Verificar usuarios con email
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM usuario WHERE email IS NOT NULL AND email != ''");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $totalUsuarios = $row['total'];
    
    if ($totalUsuarios > 0) {
        echo "<span class='success'>✓ Usuarios con email: $totalUsuarios</span><br>";
        
        // Mostrar algunos ejemplos
        $stmt2 = $conn->prepare("SELECT u.id_usuario, u.email, p.nombre, p.apellido 
                                FROM usuario u 
                                JOIN persona p ON u.id_persona = p.id_persona 
                                WHERE u.email IS NOT NULL AND u.email != '' 
                                LIMIT 3");
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        echo "<div class='info'>Ejemplos de usuarios:</div>";
        while ($usuario = $result2->fetch_assoc()) {
            echo "<div>&nbsp;&nbsp;- ID {$usuario['id_usuario']}: {$usuario['nombre']} {$usuario['apellido']} ({$usuario['email']})</div>";
        }
    } else {
        echo "<span class='error'>✗ No hay usuarios con emails configurados</span><br>";
    }
    
} catch (Exception $e) {
    echo "<span class='error'>✗ Error de base de datos: " . $e->getMessage() . "</span><br>";
}
echo "</div>";

// Prueba de configuración PHPMailer
echo "<div class='section'>";
echo "<h2>3. 📧 Prueba de Configuración PHPMailer</h2>";

if ($allEnvOk) {
    $mail = new PHPMailer(true);
    
    try {
        // Configuración básica
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = $_ENV['MAIL_SMTPAuth'] === 'true';
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = intval($_ENV['MAIL_PORT']);
        
        // Configuración adicional para Gmail
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        echo "<span class='success'>✓ Configuración PHPMailer completada</span><br>";
        
        // Intentar conectar sin enviar
        try {
            $mail->smtpConnect();
            echo "<span class='success'>✓ Conexión SMTP exitosa</span><br>";
            $mail->smtpClose();
        } catch (Exception $e) {
            echo "<span class='error'>✗ Error de conexión SMTP: " . $e->getMessage() . "</span><br>";
        }
        
    } catch (Exception $e) {
        echo "<span class='error'>✗ Error configuración PHPMailer: " . $e->getMessage() . "</span><br>";
    }
} else {
    echo "<span class='warning'>⚠️ No se puede probar PHPMailer - Variables de entorno faltantes</span><br>";
}
echo "</div>";

// Prueba de envío real (opcional)
if (isset($_GET['test_send']) && $_GET['test_send'] === 'yes' && $allEnvOk) {
    echo "<div class='section'>";
    echo "<h2>4. 📬 Prueba de Envío Real</h2>";
    
    $mail = new PHPMailer(true);
    
    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = $_ENV['MAIL_SMTPAuth'] === 'true';
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = intval($_ENV['MAIL_PORT']);
        
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        // Debug habilitado
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';
        
        $mail->setFrom($_ENV['MAIL_USERNAME'], 'La Canchita de los Pibes - TEST');
        $mail->addAddress($_ENV['MAIL_USERNAME']); // Enviar a nosotros mismos
        
        $mail->isHTML(true);
        $mail->Subject = 'Prueba Sistema Correos - ' . date('Y-m-d H:i:s');
        $mail->Body = '<h3>¡Prueba exitosa!</h3><p>El sistema de correos está funcionando correctamente.</p>';
        
        echo "<pre>";
        $mail->send();
        echo "</pre>";
        echo "<span class='success'>✓ ¡Correo de prueba enviado exitosamente!</span><br>";
        
    } catch (Exception $e) {
        echo "<span class='error'>✗ Error al enviar correo de prueba:</span><br>";
        echo "<div class='error'>Mensaje: " . $e->getMessage() . "</div>";
        echo "<div class='error'>ErrorInfo: " . $mail->ErrorInfo . "</div>";
    }
    echo "</div>";
}

// Verificar logs si existen
echo "<div class='section'>";
echo "<h2>5. 📝 Logs del Sistema</h2>";

$logFile = __DIR__ . '/logs/mail_debug.log';
if (file_exists($logFile)) {
    echo "<span class='info'>Archivo de log encontrado:</span><br>";
    $logs = file_get_contents($logFile);
    echo "<pre>" . htmlspecialchars(substr($logs, -2000)) . "</pre>"; // Últimos 2000 caracteres
} else {
    echo "<span class='warning'>⚠️ No se encontró archivo de log. Se creará automáticamente en la próxima reserva.</span><br>";
}
echo "</div>";

?>

<div class="section">
    <h2>6. 🔧 Acciones de Diagnóstico</h2>
    
    <?php if (!isset($_GET['test_send'])): ?>
    <p><strong>Para hacer una prueba completa de envío:</strong></p>
    <a href="?test_send=yes" style="background: #007cba; color: white; padding: 10px 15px; text-decoration: none; border-radius: 3px;">
        🚀 Ejecutar Prueba de Envío
    </a>
    <?php endif; ?>
    
    <h3>🧪 Tests Adicionales:</h3>
    <div style="margin: 15px 0;">
        <a href="src/Test/test_mail.php" target="_blank" style="background: #28a745; color: white; padding: 8px 12px; text-decoration: none; border-radius: 3px; margin-right: 10px;">
            � Test Básico de Correo
        </a>
        <a href="src/Test/test_reserva_completa.php" target="_blank" style="background: #17a2b8; color: white; padding: 8px 12px; text-decoration: none; border-radius: 3px; margin-right: 10px;">
            🏟️ Test Completo de Reserva
        </a>
        <a href="src/Test/verificar_bd.php" target="_blank" style="background: #ffc107; color: black; padding: 8px 12px; text-decoration: none; border-radius: 3px;">
            🗄️ Verificar Base de Datos
        </a>
    </div>
    
    <h3>�📋 Pasos Recomendados:</h3>
    <ol>
        <li><strong>Verificar configuración Gmail:</strong> 
            <ul>
                <li>Asegúrate de que la verificación en 2 pasos esté habilitada</li>
                <li>Usa una contraseña de aplicación específica (no tu contraseña de Gmail)</li>
                <li>Verifica que el email <code><?php echo $_ENV['MAIL_USERNAME'] ?? 'NO_CONFIGURADO'; ?></code> sea correcto</li>
            </ul>
        </li>
        <li><strong>Revisar firewall/antivirus:</strong> Algunos bloquean conexiones SMTP</li>
        <li><strong>Probar desde otro servidor:</strong> Algunos proveedores bloquean SMTP en localhost</li>
        <li><strong>Verificar logs:</strong> Revisar <code>/logs/mail_debug.log</code> después de cada intento</li>
    </ol>
    
    <h3>🐛 Para Debug de Reservas:</h3>
    <ol>
        <li>Ejecutar primero el <strong>Test de Verificar BD</strong> para asegurar que los datos estén correctos</li>
        <li>Luego ejecutar el <strong>Test Básico de Correo</strong> para verificar la configuración de PHPMailer</li>
        <li>Finalmente ejecutar el <strong>Test Completo de Reserva</strong> para simular todo el proceso</li>
        <li>Hacer una reserva real en el sistema</li>
        <li>Revisar el archivo <code>/logs/mail_debug.log</code></li>
    </ol>
</div>

</body>
</html>
