<!-- Example Code Start-->
<nav class="navbar navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <div class="row w-100 align-items-center">
      <!-- Columna izquierda: menú lateral -->
      <div class="col-4 d-flex p-3">
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="offcanvas"
          data-bs-target="#offcanvasDarkNavbar"
          aria-controls="offcanvasDarkNavbar"
          aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
      <!-- Columna central: logo -->
      <div class="col-4 d-flex justify-content-center">
        <a class="navbar-brand mx-auto" href="<?php echo BASE_URL; ?>/index.php">
          <figure class="m-0">
            <img src="<?php echo BASE_URL; ?>/src/Public/Logo.png" class="logo-navbar" width="60" height="60" alt="Cartoon soccer field with children playing and the text La canchita de los pibes in a cheerful and welcoming style">
          </figure>
        </a>
      </div>
      <!-- Columna derecha: botones usuario -->
      <div class="col-4 d-flex justify-content-end align-items-center">
        <?php if (!isset($_SESSION['email']) || empty($_SESSION['email'])) { ?><!-- VERIFICA SI ESTA LOGUEADO/ SI NO LO ESTA, MUESTRA LOS DOS BOTONES-->
          <button type="button" class="btn me-2 bnt-ingresar" data-bs-toggle="modal" data-bs-target="#modalLoguin">
            Ingresar
          </button>
          <button type="button" class="btn bnt-registrar" data-bs-toggle="modal" data-bs-target="#modalRegistrar">
            Registrate
          </button>
        <?php } else { ?>
          <!--SI ESTA LOGUIEADO MUESTRA EL BOTON DE CERRAR SESION-->
          <a href="<?php echo BASE_URL; ?>/src/Controllers/cerrarSesion.php" class="btn btn-danger">
            <i class="bi bi-box-arrow-right"></i>
          </a>
        <?php } ?>
      </div>
    </div>
  </div>

  <!-- Offcanvas -->
  <div
    class="offcanvas offcanvas-start text-bg-dark"
    tabindex="-1"
    id="offcanvasDarkNavbar"
    aria-labelledby="offcanvasDarkNavbarLabel">
    <div class="offcanvas-header bg-secondary text-white">
      <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
        Bienvenido:
        <?php
        echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8') : 'Inicie Sesion';
        echo ' / ';
        echo isset($_SESSION['nombre_rol']) ? htmlspecialchars($_SESSION['nombre_rol'], ENT_QUOTES, 'UTF-8') : '';
        ?>
      </h5>
      <button
        type="button"
        class="btn-close btn-close-white"
        data-bs-dismiss="offcanvas"
        aria-label="Close"></button>
    </div>

    <!--LISTADO DE TABLA LATERAL SEGUN ROL-->
    <div class="list-group-flush text-start p-3 m-0 border-0 bd-example m-0 border-0  ">
      <ul class="list-group list-group-flush text-white text-start ">
        <li class="list-group-item text-white">
          <a href="<?php echo BASE_URL; ?>/index.php" class="bg-dark text-white text-decoration-none">Home</a>
        </li>
        <!--SE MUESTRA SEGUN ROL-->
        <?php require_once __DIR__ . '/../Controllers/navBarListGroup.php'; ?>
      </ul>
    </div>


    <!--LISTADO DE TABLA LATERAL SEGUN ROL-->

  </div>
</nav>