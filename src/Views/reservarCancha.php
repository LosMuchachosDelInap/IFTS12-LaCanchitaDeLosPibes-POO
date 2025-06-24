<?php
// filepath: c:\xampp\htdocs\Mis_Proyectos\IFTS12-LaCanchitaDeLosPibes-POO\src\Views\reservarCancha.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!defined('BASE_URL')) {
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $carpeta = '/Mis_Proyectos/IFTS12-LaCanchitaDeLosPibes-POO'; // Ajusta según tu entorno
    define('BASE_URL', $protocolo . $host . $carpeta);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../ConectionBD/CConection.php';
$conn = (new ConectionDB())->getConnection();

// Selección de cancha (puedes agregar un select para elegirla)
$id_cancha = $_GET['cancha'] ?? 1;

// Obtener reservas existentes para la cancha seleccionada
$reservas = [];
$sql = "SELECT r.id_reserva, r.id_cancha, f.fecha, h.horario
        FROM reserva r
        JOIN fecha f ON r.id_fecha = f.id_fecha
        JOIN horario h ON r.id_horario = h.id_horario
        WHERE r.id_cancha = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cancha);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $reservas[] = [
        'title' => 'Reservado',
        'start' => $row['fecha'] . 'T' . $row['horario'],
        'color' => '#ccc'
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<?php require_once __DIR__ . '/../Template/head.php'; ?>
<body>
<div class="container mt-4">
    <h2>Calendario de Reservas</h2>
    <?php if (isset($_SESSION['reserva_ok'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['reserva_ok']; unset($_SESSION['reserva_ok']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['reserva_error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['reserva_error']; unset($_SESSION['reserva_error']); ?></div>
    <?php endif; ?>

    <!-- Contenedor del calendario -->
    <div id="calendar"></div>

    <div class="mt-3">
        <span style="display:inline-block;width:20px;height:20px;background:#4caf50;"></span> Libre
        <span style="display:inline-block;width:20px;height:20px;background:#ccc;margin-left:10px;"></span> Reservado
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var reservas = <?php echo json_encode($reservas); ?>;

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        slotMinTime: "08:00:00",
        slotMaxTime: "21:00:00",
        allDaySlot: false,
        locale: 'es',
        events: reservas,
        selectable: true,
        selectOverlap: false,
        select: function(info) {
            // Solo permite seleccionar slots de 1 hora
            var start = info.startStr;
            var end = info.endStr;
            var startDate = new Date(start);
            var endDate = new Date(end);
            if ((endDate - startDate) !== 60*60*1000) {
                alert('Solo puedes reservar turnos de 1 hora.');
                calendar.unselect();
                return;
            }
            // Confirmar reserva
            if (confirm('¿Reservar el turno ' + start.replace('T', ' ') + '?')) {
                // Enviar reserva por AJAX
                $.post('<?php echo BASE_URL; ?>/src/Controllers/reservarCanchaController.php', {
                    action: 'reservar',
                    cancha: <?php echo $id_cancha; ?>,
                    fecha: start.split('T')[0],
                    horario: start.split('T')[1],
                    precio: 1 // Ajusta según tu lógica
                }, function(response) {
                    if (response === 'ok') {
                        alert('¡Reserva realizada!');
                        location.reload();
                    } else {
                        alert(response);
                    }
                });
            }
            calendar.unselect();
        },
        eventClick: function(info) {
            alert('Este turno ya está reservado.');
        }
    });
    calendar.render();
});
</script>
</body>
</html>