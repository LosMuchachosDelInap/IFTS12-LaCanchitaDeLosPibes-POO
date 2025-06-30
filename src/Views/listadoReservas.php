<?php
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

// Llamo al archivo de las peticiones SQL
require_once __DIR__ . '/../Model/peticionesSql.php';

// Verifica si está logueado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ' . BASE_URL . '/Views/noInicioSesion.php');
    exit;
}

// Verifica si el rol NO es Administrador ni Dueño
$rol = $_SESSION['nombre_rol'] ?? '';
if ($rol !== 'Administrador' && $rol !== 'Dueño') {
    header('Location: ' . BASE_URL . '/Views/noAutorizado.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<?php include_once __DIR__ . '/../Template/head.php'; ?>

<body class="content">
    <?php include_once __DIR__ . '/../Template/navBar.php'; ?>
    <div class="centrar">
        <div class="mt-5 card col-10 bg-dark text-white border border-secondary">
            <!-- Mensaje de Eliminar usuario con exito
         * Si hay un mensaje en la sesión, lo muestra
         * y luego lo desaparece solo
         */-->
            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="alert alert-success" id="mensaje-flash">
                    <?= htmlspecialchars($_SESSION['mensaje'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php unset($_SESSION['mensaje']);
            endif; ?>
            Usuario: <?php echo htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8'); ?> |
            Rol: <?php echo htmlspecialchars($_SESSION['nombre_rol'], ENT_QUOTES, 'UTF-8'); ?>
            </h5>
            <div class="card-body bg-dark">
                <!-- Filtros de fecha -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" class="d-flex align-items-end gap-3">
                            <div class="form-group">
                                <label for="fecha_desde" class="form-label text-white">Desde:</label>
                                <input type="date" 
                                       id="fecha_desde" 
                                       name="fecha_desde" 
                                       class="form-control" 
                                       value="<?= $_GET['fecha_desde'] ?? '' ?>">
                            </div>
                            <div class="form-group">
                                <label for="fecha_hasta" class="form-label text-white">Hasta:</label>
                                <input type="date" 
                                       id="fecha_hasta" 
                                       name="fecha_hasta" 
                                       class="form-control" 
                                       value="<?= $_GET['fecha_hasta'] ?? '' ?>">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                                <a href="?" class="btn btn-secondary">Limpiar</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Contador de reservas -->
                <div class="row mb-2">
                    <div class="col-12">
                        <p class="text-info">
                            <strong>Total de reservas: <?= count($reservas) ?></strong>
                            <?php if (isset($_GET['fecha_desde']) || isset($_GET['fecha_hasta'])): ?>
                                (Filtradas)
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                
                <div class="text-center">
                    <table class="table table-dark text-center">
                        <thead>
                            <tr class="table-dark rounded">
                                <th>Cancha</th>
                                <th>Fecha</th>
                                <th>Horario</th>
                                <th>Precio</th>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($reservas) && is_array($reservas)): ?>
                                <?php foreach ($reservas as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['nombreCancha']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($row['fecha'])) ?></td>
                                        <td><?= date('H:i', strtotime($row['horario'])) ?> hs</td>
                                        <td>$<?= number_format($row['precio'], 0, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td><?= htmlspecialchars($row['telefono']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-calendar-times"></i>
                                        No hay reservas registradas
                                        <?php if (isset($_GET['fecha_desde']) || isset($_GET['fecha_hasta'])): ?>
                                            para el período seleccionado.
                                        <?php else: ?>
                                            en el sistema.
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include_once __DIR__ . '/../Template/footer.php'; ?>
</body>

</html>