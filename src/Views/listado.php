<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!defined('BASE_URL')) {
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $carpeta = '';
    define('BASE_URL', $protocolo . $host . $carpeta);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
            <h5 class="card-header border border-secondary">
                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="alert alert-success" id="mensaje-flash">
                        <?= htmlspecialchars($_SESSION['mensaje'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <?php unset($_SESSION['mensaje']); ?>
                <?php endif; ?>
                Usuario: <?= htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8'); ?> |
                Rol: <?= htmlspecialchars($_SESSION['nombre_rol'], ENT_QUOTES, 'UTF-8'); ?>
            </h5>
            <div class="card-body bg-dark">
                <div class="text-center">
                    <div class="row">
                        <!-- Formulario crear empleado -->
                        <div class="col-2">
                            <h5 class="alert alert-secondary text-bg-dark">Ingrese empleado</h5>
                            <form method="post" action="listadoEmpleadosController.php" class="d-grid bg-dark p-2 rounded border border-secondary" id="formCrearEmpleado">
                                <?php if (!empty($mensajeError)): ?>
                                    <div class="alert alert-danger py-1"><?= htmlspecialchars($mensajeError) ?></div>
                                <?php endif; ?>
                                <input type="email" name="email" id="email" placeholder="usuario" class="mt-2 form-control">
                                <input type="password" name="clave" id="clave" placeholder="clave" class="mt-2 form-control">
                                <input type="text" name="nombre" id="nombre" placeholder="nombre" class=" mt-2 form-control">
                                <input type="text" name="apellido" id="apellido" placeholder="apellido" class="mt-2 form-control">
                                <input type="text" name="edad" id="edad" placeholder="edad" class="mt-2 form-control">
                                <input type="text" name="dni" id="dni" placeholder="dni" class="mt-2 form-control">
                                <input type="text" name="telefono" id="telefono" placeholder="telefono" class="mt-2 form-control">
                                <div>
                                    <div class="input-group">
                                        <select name="rol" id="rol" class="mt-2 form-select form-control btn btn-secondary">
                                            <?php foreach ($roles as $rol): ?>
                                                <!-- Se muestran los roles disponibles -->
                                                <option value="<?= $rol["id_roles"] ?>"><?= htmlspecialchars($rol["rol"]) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" name="crearEmpleado" id="crearEmpleadoBtn" class="mt-2 btn btn-primary form-control" disabled>Crear empleado</button>
                            </form>
                        </div>
                        <!-- Tabla de empleados -->
                        <div class="col-10">
                            <table class="table table-dark text-center">
                                <thead>
                                    <tr class="table-dark rounded">
                                        <th scope="col">#</th>
                                        <th scope="col">Usuario</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Apellido</th>
                                        <th scope="col">Edad</th>
                                        <th scope="col">Dni</th>
                                        <th scope="col">Telefono</th>
                                        <th scope="col">Rol</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($empleados)): ?>
                                        <?php foreach ($empleados as $row): ?>
                                            <tr>
                                                <td><?= $row["id_empleado"]; ?></td>
                                                <td><?= htmlspecialchars($row["email"]); ?></td>
                                                <td><?= htmlspecialchars($row["nombre"]); ?></td>
                                                <td><?= htmlspecialchars($row["apellido"]); ?></td>
                                                <td><?= htmlspecialchars($row["edad"]); ?></td>
                                                <td><?= htmlspecialchars($row["dni"]); ?></td>
                                                <td><?= htmlspecialchars($row["telefono"]); ?></td>
                                                <td><?= htmlspecialchars($row["rol"]); ?></td>
                                                <td>
                                                    <button class="btn btn-primary" onclick="abrirModalModificar(<?= $row['id_empleado']; ?>)"><i class="bi bi-pencil-square"></i></button>
                                                    <a class="btn btn-danger" href="<?= BASE_URL; ?>/src/Controllers/eliminarEmpleado.php?id_empleado=<?= $row["id_empleado"]; ?>"><i class="bi bi-trash3-fill"></i></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9">No hay empleados registrados.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once __DIR__ . '/../Template/footer.php'; ?>
    <?php include_once __DIR__ . '/../Components/modalContactos.php'; ?>
    <?php include_once(__DIR__ . '/../Components/modalModificarEmpleado.php'); ?>
    <script>
        // Validación en el cliente para deshabilitar el botón si algún campo está vacío
        document.addEventListener('DOMContentLoaded', function() {
            const campos = ['email', 'clave', 'nombre', 'apellido', 'edad', 'dni', 'telefono', 'rol'];
            const btn = document.getElementById('crearEmpleadoBtn');
            campos.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.addEventListener('input', validarCampos);
            });

            function validarCampos() {
                let vacio = false;
                campos.forEach(id => {
                    const el = document.getElementById(id);
                    if (el && !el.value.trim()) vacio = true;
                });
                if (btn) btn.disabled = vacio;
            }
            if (btn) validarCampos();
        });

        // Mensaje flash que desaparece
        document.addEventListener('DOMContentLoaded', function() {
            const mensajeFlash = document.getElementById('mensaje-flash');
            if (mensajeFlash) {
                setTimeout(() => {
                    mensajeFlash.style.display = 'none';
                }, 3000);
            }
        });
 const BASE_URL = "<?= BASE_URL ?>";
        // Abre el modal modificar con los datos cargados del empleado a modificar
        function abrirModalModificar(id_empleado) {
            document.getElementById('modalModificarEmpleadoBody').innerHTML = '<div class="text-center p-3">Cargando...</div>';
            var modal = new bootstrap.Modal(document.getElementById('modalModificarEmpleado'));
            modal.show();

            fetch(BASE_URL + '/src/Views/modificar.php?id_empleado=' + id_empleado)
                .then(response => response.text())
                .then(html => {
                    var tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    var form = tempDiv.querySelector('form');
                    if (form) {
                        document.getElementById('modalModificarEmpleadoBody').innerHTML = '';
                        document.getElementById('modalModificarEmpleadoBody').appendChild(form);
                    } else {
                        document.getElementById('modalModificarEmpleadoBody').innerHTML = '<div class="alert alert-danger">No se pudo cargar el formulario.</div>';
                    }
                })
                .catch(() => {
                    document.getElementById('modalModificarEmpleadoBody').innerHTML = '<div class="alert alert-danger">Error al cargar el formulario.</div>';
                });
        }
    </script>
</body>

</html>