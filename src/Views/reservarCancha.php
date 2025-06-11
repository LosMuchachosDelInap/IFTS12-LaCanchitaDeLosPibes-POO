<?php
// Definir BASE_URL solo si no está definida
if (!defined('BASE_URL')) {
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    //  $carpeta = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    // $carpeta = '/Mis_proyectos/IFTS12-LaCanchitaDeLosPibes';// XAMPP
    $carpeta = ''; // localhost:8000
    define('BASE_URL', $protocolo . $host . $carpeta);
}
// Mostrar errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Inicia la sesión antes de cualquier salida
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Llamo al archivo de la clase de conexión (lo requiero para poder instanciar la clase)
require_once __DIR__ . '/../ConectionBD/CConection.php';
// Instanciao la clase
$conectarDB = new ConectionDB();
// Obtengo la conexión
$conn = $conectarDB->getConnection();

//guarda el usuario logueado
$usuarioLogueado = $_SESSION['email'] ?? null;

?>
<!DOCTYPE html>
<html lang="es">

<?php require_once __DIR__ . '/../Template/head.php'; ?>

<body class="content">

    <?php require_once __DIR__ . '/../Template/navBar.php'; ?>

    <div class="centrar">
        <h1>
            <span class="text-danger">¡Página en construcción!</span>
            <br>
            <span class="text-warning">Pronto estará disponible para reservar canchas.</span>
        </h1>
        <img src="../Public/Pagina-en-construccion3-bis.png" alt="Pagina en construccion ">

    </div>

    <?php include_once(__DIR__ . '/../Template/footer.php'); ?>
    <?php
      include_once(__DIR__ . "/../Components/modalLoguin.php");
      include_once(__DIR__ . "/../Components/modalRegistrar.php");
      include_once(__DIR__ . "/../Components/modalContactos.php");
      ?>
</body>

</html>