<?php
// filepath: c:\xampp\htdocs\Mis_Proyectos\IFTS12-LaCanchitaDeLosPibes-POO\src\Views\reservarCancha.php

if (!defined('BASE_URL')) {
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $carpeta = '/Mis_Proyectos/IFTS12-LaCanchitaDeLosPibes-POO'; // AJUSTA según tu entorno
    define('BASE_URL', $protocolo . $host . $carpeta);
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../ConectionBD/CConection.php';
$conn = (new ConectionDB())->getConnection();

// Cargar opciones de canchas, fechas, horarios y precios
$canchas = $conn->query("SELECT id_cancha, nombreCancha FROM cancha WHERE habilitado=1 AND cancelado=0");
$fechas = $conn->query("SELECT id_fecha, fecha FROM fecha WHERE habiltado=1 AND cancelado=0");
$horarios = $conn->query("SELECT id_horario, horario FROM horario WHERE habiltado=1 AND cancelado=0");
$precios = $conn->query("SELECT id_precio, precio FROM precio WHERE habiltado=1 AND cancelado=0");
?>
<!DOCTYPE html>
<html lang="es">
<?php require_once __DIR__ . '/../Template/head.php'; ?>
<body class="content">
    <?php require_once __DIR__ . '/../Template/navBar.php'; ?>
    <div class="centrar">
        <h2>Reservar Cancha</h2>
        <?php if (isset($_SESSION['reserva_ok'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['reserva_ok']; unset($_SESSION['reserva_ok']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['reserva_error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['reserva_error']; unset($_SESSION['reserva_error']); ?></div>
        <?php endif; ?>
        <form method="post" action="<?php echo BASE_URL; ?>/src/Controllers/reservarCanchaController.php" class="bg-dark p-3 rounded">
            <div class="mb-2">
                <label>Cancha:</label>
                <select name="cancha" class="form-select">
                    <option value="">Selecciona una cancha</option>
                    <?php while ($c = $canchas->fetch_assoc()): ?>
                        <option value="<?php echo $c['id_cancha']; ?>"><?php echo $c['nombreCancha']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-2">
                <label>Fecha:</label>
                <select name="fecha" class="form-select">
                    <option value="">Selecciona una fecha</option>
                    <?php while ($f = $fechas->fetch_assoc()): ?>
                        <option value="<?php echo $f['id_fecha']; ?>"><?php echo $f['fecha']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-2">
                <label>Horario:</label>
                <select name="horario" class="form-select">
                    <option value="">Selecciona un horario</option>
                    <?php while ($h = $horarios->fetch_assoc()): ?>
                        <option value="<?php echo $h['id_horario']; ?>"><?php echo $h['horario']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-2">
                <label>Precio:</label>
                <select name="precio" class="form-select">
                    <option value="">Selecciona un precio</option>
                    <?php while ($p = $precios->fetch_assoc()): ?>
                        <option value="<?php echo $p['id_precio']; ?>"><?php echo $p['precio']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Confirmar Reserva</button>
        </form>
    </div>
    <?php include_once(__DIR__ . '/../Template/footer.php'); ?>
</body>
</html>