<?php

// Definir BASE_URL solo si no está definida
if (!defined('BASE_URL')) {
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    //$carpeta = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');// fija la ruta hasta la carpeta en donde esta el archivo que estoy usando o abriendo
    //$carpeta = '/Mis_Proyectos/IFTS12-LaCanchitaDeLosPibes';// XAMPP
     $carpeta = ''; // SIN subcarpeta// POR PHP - s LOCALHOST:8000
    define('BASE_URL', $protocolo . $host . $carpeta);
}

session_start();
unset($_SESSION['logged_in']);
// cierra la sesion
session_destroy();
// redirigir a la página de inicio de sesión
// RUTA GENERAL
header('Location: ' . BASE_URL . '/index.php');
// header('Location: /index.php');// usar en casa
// header('Location: /Mis%20proyectos/IFTS12-LaCanchitaDeLosPibes/index.php');// usar en el trabajo
exit;
?>