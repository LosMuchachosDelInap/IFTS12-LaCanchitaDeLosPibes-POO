<?php

$rol = $_SESSION['nombre_rol'] ?? '';
?>

<?php if ($rol === 'Administrador' || $rol === 'Dueño'): ?>
    <!--SE MUESTRA SI ES ADMINISTRADOR O DUEÑO-->
    <li class=" list-group-item">

        <a href="<?php echo BASE_URL; ?>/src/Controllers/listadoEmpleadosController.php" class="text-white text-decoration-none">Listado de empleados</a>
    </li>
    <li class=" list-group-item">

        <a href="<?php echo BASE_URL; ?>/src/Controllers/listadoReservasController.php" class="text-white text-decoration-none">Listado de reservas</a>
    </li>
    <li class=" list-group-item"> <a href="<?php echo BASE_URL; ?>/src/Views/reservarCancha.php" class="text-white text-decoration-none">Reservar cancha</a></li>
<?php elseif ($rol !== 'Administrador' || $rol !== 'Dueño'): ?>
    <!--SI NO ES ADMINISTRADOR NI DUEÑO-->
    <li class=" list-group-item"> <a href="<?php echo BASE_URL; ?>/src/Views/reservarCancha.php" class="text-white text-decoration-none">Reservar cancha</a></li>
<?php endif; ?>