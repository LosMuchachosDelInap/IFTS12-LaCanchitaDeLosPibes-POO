<?php
// Configurar logging específico para correos
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../logs/mail_debug.log');

// Crear directorio de logs si no existe
if (!file_exists(__DIR__ . '/../../logs')) {
    mkdir(__DIR__ . '/../../logs', 0755, true);
}

// Función helper para logging
function logMail($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message" . PHP_EOL;
    file_put_contents(__DIR__ . '/../../logs/mail_debug.log', $logMessage, FILE_APPEND | LOCK_EX);
}
?>
