<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ...código para obtener datos del empleado y roles...
?>
<form method="post" action="<?php echo BASE_URL; ?>/src/Controllers/modificarEmpleado.php" class="d-grid bg-dark p-2 rounded">
    <input type="hidden" name="id_persona" value="<?php echo $empleado["id_persona"]; ?>">
    <input type="hidden" name="id_usuario" value="<?php echo $empleado["id_usuario"]; ?>">
    <input type="hidden" name="id_empleado" value="<?php echo $empleado["id_empleado"]; ?>">
    <select name="rol" class="mt-2 form-select form-control btn btn-secondary">
        <?php foreach ($roles as $rol) { ?>
            <option value="<?php echo $rol["id_roles"]; ?>" <?php if ($empleado["id_rol"] == $rol["id_roles"]) echo 'selected'; ?>>
                <?php echo $rol["rol"]; ?>
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

