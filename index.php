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

<body>
      <?php include_once(__DIR__ . "/src/Template/navBar.php"); ?>

      <div class="centrar">
            <!--contenido central de la pagina-->
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
</body>

</html>