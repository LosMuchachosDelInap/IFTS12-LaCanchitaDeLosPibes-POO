<div class="modal fade" id="modalContactos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 text-dark" id="exampleModalLabel">New message</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="<?php echo BASE_URL; ?>/src/Controllers/contactoController.php"> <!-- para usar en casa -->
       <!-- <form method="post" action="/Mis%20proyectos/IFTS12-LaCanchitaDeLosPibes/src/Controllers/contacto.php"> --><!-- para usar en EL TRABAJO -->
          <div id="contacto-mensaje"></div>
          <div class="mb-3">
            <?php
            //---------------------------------------------------------------------------------------------------------------------------
            // Verifica si está logueado y muestra la etiqueta correspondiente 
            if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
              // Si no está logueado, muestra "Ingrese su email"
            ?>
              <label for="usuario" class="col-form-label text-dark text-start">Ingrese su emal</label>
            <?php
            } else {
            ?>
              <label for="usuario" class="col-form-label text-dark text-start">Usuario</label>
            <?php
            }
            //---------------------------------------------------------------------------------------------------------------------------
            ?>
            <!--Verifica si hay alguien logueado,si hay muestra el email ,sino, lo deja en blanco para que se llene manualmente-->
            <input type="email" name="email" class="form-control" id="usuario"
              value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
              <?php echo (isset($_SESSION['email']) ? 'readonly' : 'required'); ?>>
          </div>
          <div class="mb-3">
            <label for="consulta" class="col-form-label text-dark text-start">Escribanos su cansulta:</label>
            <textarea name="mensaje" class="form-control" id="consulta"></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Enviar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Mensajes de error sin recargar la pagina-->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#modalContactos form');
    const mensajeDiv = document.getElementById('contacto-mensaje');

    form.addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(form);

      fetch(form.action, {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          mensajeDiv.innerHTML = data;
          if (data.includes('alert-success')) {
            form.querySelector('textarea').value = '';
          }
        })
        .catch(error => {
          mensajeDiv.innerHTML = "<div class='alert alert-danger'>Error de conexión.</div>";
        });
    });
  });
</script>