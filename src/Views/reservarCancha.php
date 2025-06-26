<?php
// muestra los errores en el navegador ,soi los hay
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definir BASE_URL solo si no está definida
if (!defined('BASE_URL')) {
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    //  $carpeta = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    // $carpeta = '/Mis_proyectos/IFTS12-LaCanchitaDeLosPibes';// XAMPP
    $carpeta = ''; // localhost:8000
    define('BASE_URL', $protocolo . $host . $carpeta);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../ConectionBD/CConection.php';
$conn = (new ConectionDB())->getConnection();

// carga el select de canchas
$canchas = [];
$result = $conn->query("SELECT id_cancha, nombreCancha, precio FROM cancha WHERE habilitado = 1 AND cancelado = 0");
while ($row = $result->fetch_assoc()) {
    $canchas[] = $row;
}

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

<body class="content">
    <!--Chequea que alla usuario logueado, si lo esta,lo guarda en la variable-->
    <script>
        var usuarioLogueado = <?php echo isset($_SESSION['id_usuario']) ? 'true' : 'false'; ?>;
    </script>
    <!---------------------------------------------------------------------------------------------------->
    <?php require_once __DIR__ . '/../Template/navBar.php'; ?>
    <div class="centrar">
        <h2>Calendario de Reservas</h2>
        <?php if (isset($_SESSION['reserva_ok'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['reserva_ok'];
                                                unset($_SESSION['reserva_ok']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['reserva_error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['reserva_error'];
                                            unset($_SESSION['reserva_error']); ?></div>
        <?php endif; ?>
        <!-- Seleccionar la cancha a reservar -->
        <form method="get" class="mb-3 text-center">
            <label for="cancha">Seleccioná la cancha:</label>
            <select name="cancha" id="cancha" onchange="this.form.submit()">
                <?php foreach ($canchas as $cancha): ?>
                    <option value="<?= $cancha['id_cancha'] ?>" <?= ($id_cancha == $cancha['id_cancha']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cancha['nombreCancha']) ?> - $<?= number_format($cancha['precio'], 0, ',', '.') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php
        // muestra la cancha seleccionada       
        $canchaSeleccionada = null;
        foreach ($canchas as $cancha) {
            if ($cancha['id_cancha'] == $id_cancha) {
                $canchaSeleccionada = $cancha;
                break;
            }
        }
        if ($canchaSeleccionada):
        ?>
            <div class="mb-2 text-center">
                <strong>Cancha seleccionada:</strong>
                <?= htmlspecialchars($canchaSeleccionada['nombreCancha']) ?>
                <span class="badge bg-success">$<?= number_format($canchaSeleccionada['precio'], 0, ',', '.') ?></span>
            </div>
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
                slotDuration: '01:00:00',
                select: function(info) {
                    if (!usuarioLogueado) {
                        // Mostrar modal de login
                        var modalLoguin = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalLoguin'));
                        modalLoguin.show();
                        calendar.unselect();
                        return;
                    }
                    var startDate = info.start;
                    var endDate = info.end;
                    var minutos = Math.abs((endDate - startDate) / (1000 * 60));
                    if (minutos !== 60) {
                        alert('Solo puedes reservar turnos de 1 hora.');
                        calendar.unselect();
                        return;
                    }
                    if (confirm('¿Reservar el turno ' + info.startStr.replace('T', ' ') + '?')) {
                        // Forzar formato HH:MM:SS
                        var dateObj = info.start;
                        var horas = String(dateObj.getHours()).padStart(2, '0');
                        var minutos = String(dateObj.getMinutes()).padStart(2, '0');
                        var segundos = '00';
                        var horario = horas + ':' + minutos + ':' + segundos;

                        $.post('<?php echo BASE_URL; ?>/src/Controllers/reservarCanchaController.php', {
                            action: 'reservar',
                            cancha: <?php echo $id_cancha; ?>,
                            fecha: info.startStr.split('T')[0],
                            horario: horario
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
    <?php
    include_once(__DIR__ . '/../Template/footer.php');
    include_once(__DIR__ . "/../Components/modalLoguin.php");
    include_once(__DIR__ . "/../Components/modalRegistrar.php");
    include_once(__DIR__ . "/../Components/modalContactos.php");
    ?>
  <!-- Si hay un mensaje de error, muestra el modal de login-->
      <?php if (isset($_SESSION['error_message'])): ?>

            <script>
                  document.addEventListener('DOMContentLoaded', function() {
                        var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalLoguin'));
                        modal.show();
                  });
            </script>
             <?php unset($_SESSION['error_message']); ?>
      <?php endif; ?>
</body>

</html>