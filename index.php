<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Inicia la sesión antes de cualquier salida
if (session_status() === PHP_SESSION_NONE) {
      session_start();
}
// Llamo al archivo de la clase de conexión (lo requiero para poder instanciar la clase)
require_once __DIR__ . '/src/ConectionBD/CConection.php';
// Instanciao la clase
$conectarDB = new ConectionDB();
// Obtengo la conexión
$conn = $conectarDB->getConnection();
?>
<!DOCTYPE html>
<html lang="es">

<?php include_once(__DIR__ . "/src/Template/head.php"); ?>

<body class="content">
      <?php include_once(__DIR__ . "/src/Template/navBar.php"); ?>

      <div class="centrar">
            <div class="mt-5 card col-10 bg-dark text-white border border-secondary">
                  <h5 class="card-header border border-secondary">
                        🏆 Bienvenido a La Canchita de los Pibes
                        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                              | Usuario: <?= htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8'); ?>
                        <?php endif; ?>
                  </h5>
                  <div class="card-body bg-dark">
                        <div class="text-center">
                              <div class="row">
                                    <!-- Información principal -->
                                    <div class="col-12 col-md-6">
                                          <h3 class="text-success mb-4">🏈 Sistema de Reservas</h3>
                                          <p class="lead">
                                                Reserva tu cancha favorita de forma rápida y sencilla. 
                                                Gestiona tus horarios y disfruta del mejor fútbol.
                                          </p>
                                          
                                          <div class="row text-center mt-4">
                                                <div class="col-6">
                                                      <div class="bg-success bg-opacity-25 p-3 rounded">
                                                            <i class="bi bi-calendar-check fs-1 text-success"></i>
                                                            <h5 class="mt-2">Reservas Online</h5>
                                                            <p class="small">Sistema 24/7 disponible</p>
                                                      </div>
                                                </div>
                                                <div class="col-6">
                                                      <div class="bg-primary bg-opacity-25 p-3 rounded">
                                                            <i class="bi bi-people fs-1 text-primary"></i>
                                                            <h5 class="mt-2">Fácil Gestión</h5>
                                                            <p class="small">Administra tus reservas</p>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                                    
                                    <!-- Panel de acciones -->
                                    <div class="col-12 col-md-6">
                                          <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                                                <!-- Usuario logueado -->
                                                <h4 class="text-info mb-4">🎯 Panel de Usuario</h4>
                                                <div class="d-grid gap-3">
                                                      <a href="src/Views/reservarCancha.php" class="btn btn-success btn-lg">
                                                            <i class="bi bi-calendar-plus"></i> Reservar Cancha
                                                      </a>
                                                      
                                                      <?php if (in_array($_SESSION['nombre_rol'] ?? '', ['Administrador', 'Dueño'])): ?>
                                                            <a href="src/Views/listado.php" class="btn btn-primary">
                                                                  <i class="bi bi-people"></i> Gestionar Empleados
                                                            </a>
                                                            <a href="src/Views/listadoReservas.php" class="btn btn-info">
                                                                  <i class="bi bi-list-check"></i> Ver Reservas
                                                            </a>
                                                      <?php endif; ?>
                                                      
                                                      <a href="src/Controllers/cerrarSesion.php" class="btn btn-outline-danger">
                                                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                                      </a>
                                                </div>
                                                
                                                <div class="alert alert-info mt-4">
                                                      <i class="bi bi-info-circle"></i>
                                                      <strong>¡Bienvenido!</strong> Tu sesión está activa.
                                                </div>
                                          <?php else: ?>
                                                <!-- Usuario no logueado -->
                                                <h4 class="text-warning mb-4">🔐 Acceso al Sistema</h4>
                                                <p class="mb-4">Para reservar una cancha, necesitas iniciar sesión o registrarte.</p>
                                                
                                                <div class="d-grid gap-3">
                                                      <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalLoguin">
                                                            <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                                                      </button>
                                                      
                                                      <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalRegistrar">
                                                            <i class="bi bi-person-plus"></i> Registrarse
                                                      </button>
                                                      
                                                      <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalContactos">
                                                            <i class="bi bi-envelope"></i> Contactanos
                                                      </button>
                                                </div>
                                                
                                                <div class="alert alert-warning mt-4">
                                                      <i class="bi bi-exclamation-triangle"></i>
                                                      <strong>¡Regístrate!</strong> Es rápido y gratuito.
                                                </div>
                                          <?php endif; ?>
                                    </div>
                              </div>
                              
                              <!-- Información adicional -->
                              <hr class="my-4">
                              <div class="row">
                                    <div class="col-12 col-sm-4">
                                          <div class="text-center p-3">
                                                <i class="bi bi-clock fs-2 text-success"></i>
                                                <h6 class="mt-2">Horarios</h6>
                                                <p class="small text-muted">Lunes a Domingo<br>8:00 - 22:00</p>
                                          </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                          <div class="text-center p-3">
                                                <i class="bi bi-geo-alt fs-2 text-primary"></i>
                                                <h6 class="mt-2">Ubicación</h6>
                                                <p class="small text-muted">Buenos Aires<br>Argentina</p>
                                          </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                          <div class="text-center p-3">
                                                <i class="bi bi-phone fs-2 text-info"></i>
                                                <h6 class="mt-2">Contacto</h6>
                                                <p class="small text-muted">+54 11 1234-5678<br>info@canchitapibes.com</p>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
      <div class="footer">
            <?php include_once(__DIR__ . "/src/Template/footer.php"); ?>
      </div>
      <?php
      include_once(__DIR__ . "/src/Components/modalLoguin.php");
      include_once(__DIR__ . "/src/Components/modalRegistrar.php");
      include_once(__DIR__ . "/src/Components/modalContactos.php");
      ?>
      <!--al hacer click en registrar muestra el mensaje y espera 3 seg,luego se cierra y muyestra el loguin-->
      <script>
            document.addEventListener("DOMContentLoaded", function() {
                  // Verifica si hay mensaje de registro exitoso
                  <?php if (isset($_SESSION['registro_message'])): ?>
                        // Espera 3 segundos, cierra el modal de registro y abre el de login
                        setTimeout(function() {
                              var modalRegistrar = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalRegistrar'));
                              modalRegistrar.hide();
                              var modalLoguin = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalLoguin'));
                              modalLoguin.show();
                        }, 3000);
                  <?php
                        unset($_SESSION['registro_message']); // Limpia el mensaje para no mostrarlo de nuevo
                  endif; ?>
            });
      </script>
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