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
            <select name="cancha" id="cancha" onchange="this.form.submit()" class="form-select d-inline-block w-auto">
                <?php foreach ($canchas as $cancha): ?>
                    <option value="<?= $cancha['id_cancha'] ?>" <?= ($id_cancha == $cancha['id_cancha']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cancha['nombreCancha']) ?> - $<?= number_format($cancha['precio'], 0, ',', '.') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <!-- Configuración rápida de vista -->
        <div class="row mb-3">
            <div class="col-12 text-center">
                <div class="btn-group" role="group" aria-label="Vista del calendario">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="changeCalendarView('dayGridMonth')">
                        📅 Mes
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="changeCalendarView('timeGridWeek')">
                        📊 Semana
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="changeCalendarView('timeGridDay')">
                        📋 Día
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="changeCalendarView('listWeek')">
                        📝 Lista
                    </button>
                </div>
            </div>
        </div>

        <?php
        // La información de la cancha seleccionada ahora se muestra en el header de la tarjeta
        $canchaSeleccionada = null;
        foreach ($canchas as $cancha) {
            if ($cancha['id_cancha'] == $id_cancha) {
                $canchaSeleccionada = $cancha;
                break;
            }
        }
        ?>

        <!-- Contenedor del calendario con estructura de tarjeta -->
        <div class="mt-5 card col-10 bg-dark text-white border border-secondary">
            <h5 class="card-header border border-secondary">
                📅 Calendario de Reservas
                <?php if ($canchaSeleccionada): ?>
                    - <?= htmlspecialchars($canchaSeleccionada['nombreCancha']) ?>
                    <span class="badge bg-success ms-2">$<?= number_format($canchaSeleccionada['precio'], 0, ',', '.') ?></span>
                <?php endif; ?>
            </h5>
            <div class="card-body bg-dark">
                <div id="calendar"></div>
            </div>
        </div>

        <!-- Leyenda mejorada -->
        <div class="mt-3 d-flex justify-content-center align-items-center gap-4">
            <div class="legend-item">
                <span class="legend-color" style="background:#4caf50;"></span>
                <span>Libre</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background:#ccc;"></span>
                <span>Reservado</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background:#2196F3;"></span>
                <span>Seleccionando</span>
            </div>
        </div>
        
        <!-- Información adicional -->
        <div class="mt-3 text-center">
            <small class="text-muted">
                💡 <strong>Tip:</strong> Haz clic y arrastra para seleccionar un horario de 1 hora
            </small>
        </div>
    </div>

    <script>
        var calendar; // Variable global para el calendario
        
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var reservas = <?php echo json_encode($reservas); ?>;

            var calendar = new FullCalendar.Calendar(calendarEl, {
                // Configuración de vistas disponibles
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                
                // Vista inicial
                initialView: 'timeGridWeek', // Puedes cambiar a: dayGridMonth, timeGridDay, listWeek
                
                // Configuración de horarios
                slotMinTime: "08:00:00",  // Hora de inicio
                slotMaxTime: "22:00:00",  // Hora de fin
                slotDuration: '01:00:00', // Duración de cada slot (1 hora)
                slotLabelInterval: '01:00', // Intervalos en las etiquetas
                
                // Configuración general
                allDaySlot: false,       // Ocultar slot de "todo el día"
                locale: 'es',           // Idioma español
                height: 'auto',         // Altura automática
                expandRows: true,       // Expandir filas
                
                // Configuración de días
                firstDay: 1,            // Lunes como primer día (0=Domingo, 1=Lunes)
                weekends: true,         // Mostrar fines de semana
                
                // Eventos y reservas
                events: reservas,
                
                // Configuración de selección
                selectable: true,
                selectOverlap: false,
                selectMirror: true,     // Mostrar preview de selección
                
                // Configuración de tiempo
                nowIndicator: true,     // Mostrar línea de tiempo actual
                
                // Configuración visual
                eventDisplay: 'block',
                
                // Textos personalizados
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día',
                    list: 'Lista'
                },
                
                // Configuración de vista de mes
                dayMaxEvents: 3,        // Máximo 3 eventos por día en vista mensual
                moreLinkText: 'más',    // Texto para "ver más eventos"
                
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
                    
                    // Verificar que no sea una fecha pasada
                    var ahora = new Date();
                    if (startDate < ahora) {
                        alert('No puedes reservar en fechas pasadas.');
                        calendar.unselect();
                        return;
                    }
                    
                    var fechaFormateada = startDate.toLocaleDateString('es-ES');
                    var horaFormateada = startDate.toLocaleTimeString('es-ES', {hour: '2-digit', minute:'2-digit'});
                    
                    if (confirm(`¿Confirmas la reserva?\n\nFecha: ${fechaFormateada}\nHora: ${horaFormateada}\nCancha: <?= htmlspecialchars($canchaSeleccionada['nombreCancha'] ?? '') ?>\nPrecio: $<?= number_format($canchaSeleccionada['precio'] ?? 0, 0, ',', '.') ?>`)) {
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
                                alert('¡Reserva realizada exitosamente!');
                                location.reload();
                            } else {
                                alert('Error: ' + response);
                            }
                        }).fail(function() {
                            alert('Error de conexión. Inténtalo de nuevo.');
                        });
                    }
                    calendar.unselect();
                },
                
                eventClick: function(info) {
                    alert('Este turno ya está reservado.');
                },
                
                // Personalizar el contenido de los eventos
                eventContent: function(arg) {
                    return {
                        html: '<div style="font-size: 12px; text-align: center; padding: 2px;">🚫 Reservado</div>'
                    };
                },
                
                // Callback cuando cambia la vista
                viewDidMount: function(info) {
                    updateViewButtons(info.view.type);
                }
            });
            calendar.render();
        });
        
        // Función para cambiar la vista del calendario
        function changeCalendarView(viewName) {
            if (calendar) {
                calendar.changeView(viewName);
                updateViewButtons(viewName);
            }
        }
        
        // Función para actualizar los botones activos
        function updateViewButtons(currentView) {
            document.querySelectorAll('.btn-group button').forEach(btn => {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            });
            
            const viewMap = {
                'dayGridMonth': 0,
                'timeGridWeek': 1,
                'timeGridDay': 2,
                'listWeek': 3
            };
            
            const buttonIndex = viewMap[currentView];
            if (buttonIndex !== undefined) {
                const buttons = document.querySelectorAll('.btn-group button');
                if (buttons[buttonIndex]) {
                    buttons[buttonIndex].classList.remove('btn-outline-primary');
                    buttons[buttonIndex].classList.add('btn-primary');
                }
            }
        }
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