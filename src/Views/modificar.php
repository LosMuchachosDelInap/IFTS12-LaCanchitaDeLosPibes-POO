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

// Inicia la sesión antes de cualquier salida
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- AGREGADO: Cargar clases y conexión ---
require_once __DIR__ . '/../ConectionBD/CConection.php';
require_once __DIR__ . '/../Model/Empleado.php';
require_once __DIR__ . '/../Model/Persona.php';
require_once __DIR__ . '/../Model/Usuario.php';

$conn = (new ConectionDB())->getConnection();

$id_empleado = $_GET['id_empleado'] ?? null;
$empleado = null;
$roles = [];

if ($id_empleado) {
    // Obtener el empleado
    $empleadoObj = Empleado::buscarPorId($conn, $id_empleado);
    if ($empleadoObj) {
        // Obtener persona y usuario asociados
        $personaObj = Persona::buscarPorId($conn, $empleadoObj->getIdPersona());
        $usuarioObj = Usuario::buscarPorId($conn, $empleadoObj->getIdUsuario());

        // Armar array para el formulario
        $empleado = [
            "id_empleado" => $empleadoObj->getIdEmpleado(),
            "id_persona"  => $empleadoObj->getIdPersona(),
            "id_usuario"  => $empleadoObj->getIdUsuario(),
            "nombre"      => $personaObj ? $personaObj->getNombre() : '',
            "apellido"    => $personaObj ? $personaObj->getApellido() : '',
            "edad"        => $personaObj ? $personaObj->getEdad() : '',
            "dni"         => $personaObj ? $personaObj->getDni() : '',
            "telefono"    => $personaObj ? $personaObj->getTelefono() : '',
            "email"       => $usuarioObj ? $usuarioObj->getEmail() : '',
            "id_rol"      => $empleadoObj->getIdRol()
        ];
    }

    // Obtener roles
    $resultRoles = $conn->query("SELECT id_roles, rol FROM roles");
    while ($row = $resultRoles->fetch_assoc()) {
        $roles[] = $row;
    }
} else {
    echo "<div class='alert alert-danger'>No se recibió el ID del empleado.</div>";
    exit;
}
// --- FIN AGREGADO ---
?>
<!DOCTYPE html>
<html lang="es">
<?php include_once __DIR__ . '/../Template/head.php'; ?>

<body class="content">

    <?php include_once __DIR__ . '/../Template/navBar.php'; ?>
    <div class="centrar">
        <form method="post" action="<?php echo BASE_URL; ?>/src/Controllers/modificarEmpleado.php" class="d-grid bg-dark p-2 rounded">
            <input type="hidden" name="id_persona" value="<?php echo $empleado["id_persona"]; ?>">
            <input type="hidden" name="id_usuario" value="<?php echo $empleado["id_usuario"]; ?>">
            <input type="hidden" name="id_empleado" value="<?php echo $empleado["id_empleado"]; ?>">
            <select name="rol" class="mt-2 form-select form-control btn btn-secondary">
                <?php foreach ($roles as $rol) { ?>
                    <option value="<?php echo $rol['id_roles']; ?>" <?php if ($rol['id_roles'] == $empleado['id_rol']) echo 'selected'; ?>>
                        <?php echo $rol['rol']; ?>
                    </option>
                <?php } ?>
            </select>
            <input type="text" name="nombre" value="<?php echo $empleado["nombre"]; ?>" class="mt-2 form-control">
            <input type="text" name="apellido" value="<?php echo $empleado["apellido"]; ?>" class="mt-2 form-control">
            <input type="text" name="edad" value="<?php echo $empleado["edad"]; ?>" class="mt-2 form-control">
            <input type="text" name="dni" value="<?php echo $empleado["dni"]; ?>" class="mt-2 form-control">
            <input type="email" name="usuario" value="<?php echo $empleado["email"]; ?>" class="mt-2 form-control">
            <input type="password" name="clave" value="" placeholder="Nueva clave (opcional)" class="mt-2 form-control">
            <input type="text" name="telefono" value="<?php echo $empleado["telefono"]; ?>" class="mt-2 form-control">
            <button type="submit" name="modificar" class="mt-2 btn btn-primary form-control">Aceptar cambios</button>
        </form>
    </div>
    <?php
    // Footer y modal de contactos
    include_once __DIR__ . '/../Template/footer.php';
    include_once __DIR__ . '/../Components/modalContactos.php';
    ?>
</body>

</html>