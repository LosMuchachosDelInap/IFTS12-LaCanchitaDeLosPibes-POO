<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!defined('BASE_URL')) {
  $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
  $host = $_SERVER['HTTP_HOST'];
  // $carpeta = '/Mis_proyectos/IFTS12-LaCanchitaDeLosPibes';// cuando usas XAMPP
  $carpeta = ''; // cuando usas <localhost:8000>
  define('BASE_URL', $protocolo . $host . $carpeta);
}

// Inicia la sesión antes de cualquier salida
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
// Llamo al archivo de la clase de conexión (lo requiero para poder instanciar la clase)
require_once __DIR__ . '/../ConectionBD/CConection.php';
// Instancio la clase
$conectarDB = new ConectionDB();
// Obtengo la conexión
$conn = $conectarDB->getConnection();

// Llamo al archivo de las peticiones SQL
require_once __DIR__ . '/../Model/peticionesSql.php';

// Verifica si está logueado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  header('Location: ' . BASE_URL . '/Views/noInicioSesion.php');
  exit;
}

// Verifica si el rol NO es Administrador ni Dueño
$rol = $_SESSION['nombre_rol'] ?? '';
if ($rol !== 'Administrador' && $rol !== 'Dueño') {
  header('Location: ' . BASE_URL . '/Views/noAutorizado.php');
  exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<?php include_once __DIR__ . '/../Template/head.php'; ?>

<body class="content">

  <?php include_once __DIR__ . '/../Template/navBar.php'; ?>

  <div class="centrar">
    <div class="mt-5 card col-10 bg-dark text-white border border-secondary">
      <h5 class="card-header border border-secondary">
        <!-- Mensaje de Eliminar usuario con exito
         * Si hay un mensaje en la sesión, lo muestra
         * y luego lo desaparece solo
         */-->
        <?php if (isset($_SESSION['mensaje'])): ?>
          <div class="alert alert-success" id="mensaje-flash">
            <?= htmlspecialchars($_SESSION['mensaje'], ENT_QUOTES, 'UTF-8'); ?>
          </div>
        <?php unset($_SESSION['mensaje']);
        endif; ?>
        Usuario: <?php echo htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8'); ?> |
        Rol: <?php echo htmlspecialchars($_SESSION['nombre_rol'], ENT_QUOTES, 'UTF-8'); ?>
      </h5>
      <div class="card-body bg-dark">
        <div class="text-center">
          <div class="row ">
            <div class="col-2 ">
              <h5 class="alert alert-secondary text-bg-dark">Ingrese empleado</h5>
              <form method="post" action="listado.php" class="d-grid bg-dark p-2 rounded border border-secondary" id="formCrearEmpleado">
                <!-- Mensaje de error -->
                <?php if (isset($errorCampos) && $errorCampos): ?>
                  <div class="alert alert-danger py-1">Debe llenar todos los campos</div>
                <?php endif; ?>
                <!-- Mensaje de error -->
                <!-- Formulario de creación de empleado -->
                <input type="email" name="email" id="email" placeholder="usuario" class="mt-2 form-control">
                <input type="password" name="clave" id="clave" placeholder="clave" class="mt-2 form-control">
                <input type="text" name="nombre" id="nombre" placeholder="nombre" class=" mt-2 form-control">
                <input type="text" name="apellido" id="apellido" placeholder="apellido" class="mt-2 form-control">
                <input type="text" name="edad" id="edad" placeholder="edad" class="mt-2 form-control">
                <input type="text" name="dni" id="dni" placeholder="dni" class="mt-2 form-control">
                <input type="text" name="telefono" id="telefono" placeholder="telefono" class="mt-2 form-control">
                <div>
                  <div class="input-group">
                    <!-- LISTA DESPLEGABLE CARGAOS --------------------------------------->
                    <select name="rol" id="rol" class="mt-2 form-select form-control btn btn-secondary">
                      <?php
                      $listarRoles = mysqli_query($conn, $listarRol);
                      while ($row = mysqli_fetch_array($listarRoles)) { ?>
                        <option value="<?php echo $row["id_roles"] ?>"><?php echo $row["rol"] ?></option>
                      <?php } ?>
                    </select>
                    <!-- LISTA DESPLEGABLE CARGAOS --------------------------------------->
                  </div>
                </div>
                <button type="submit" name="crearEmpleado" id="crearEmpleadoBtn" class="mt-2 btn btn-primary form-control" disabled>Crear empleado</button>
                <?php
                // crear empleado
                // si se hace click en el boton de crear empleado
                // se ejecuta la consulta de crear empleado
                if (isset($_POST['crearEmpleado'])) {
                  $campos = ['email', 'clave', 'nombre', 'apellido', 'edad', 'dni', 'telefono', 'rol'];
                  $errorCampos = false;
                  foreach ($campos as $campo) {
                    if (empty($_POST[$campo])) {
                      $errorCampos = true;
                      break;
                    }
                  }
                  if ($errorCampos) {
                    echo '<div class="alert alert-danger py-1">Debe llenar todos los campos</div>';
                  } else {
                    $id_Rol = $_POST['rol'] ?? null;

                    // Insertar persona correctamente
                    $stmt = mysqli_prepare($conn, $crearPersonaQuery);
                    mysqli_stmt_bind_param($stmt, "ssiss", $_POST['apellido'], $_POST['nombre'], $_POST['edad'], $_POST['dni'], $_POST['telefono']);
                    mysqli_stmt_execute($stmt);
                    $idPersonaObtenido = mysqli_insert_id($conn);
                    mysqli_stmt_close($stmt);

                    if ($idPersonaObtenido) {
                      $clave = $_POST['clave'];
                      $hashed_password = password_hash($clave, PASSWORD_DEFAULT);
                      $registrarPersonaQuery = "INSERT INTO usuario (id_persona, email, clave) VALUES (?, ?, ?)";
                      $stmt = mysqli_prepare($conn, $registrarPersonaQuery);
                      mysqli_stmt_bind_param($stmt, "iss", $idPersonaObtenido, $_POST['email'], $hashed_password);
                      mysqli_stmt_execute($stmt);
                      $idUsuarioObtenido = mysqli_insert_id($conn);
                      mysqli_stmt_close($stmt);

                      if ($id_Rol) {
                        $crearEmpleado = "INSERT INTO empleado (id_rol, id_persona, id_usuario) VALUES (?, ?, ?)";
                        $stmt = mysqli_prepare($conn, $crearEmpleado);
                        mysqli_stmt_bind_param($stmt, "iii", $id_Rol, $idPersonaObtenido, $idUsuarioObtenido);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                      }
                      echo "<script>alert('Usuario creado exitosamente');</script>";
                    } else {
                      echo "<script>alert('Error al crear usuario');</script>";
                    }
                  }
                }
                ?>
                <?php
                // Volver al login
                if (isset($_POST['volverLogin'])) {
                  header('Location: ' . BASE_URL . '/index.php');
                }
                ?>
              </form>
            </div>
            <div class="col-10">
              <table class="table table-dark text-center">
                <thead>
                  <tr class="table-dark rounded">
                    <th scope="col">#</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido</th>
                    <th scope="col">Edad</th>
                    <th scope="col">Dni</th>
                    <th scope="col">Telefono</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $listarRegistros = mysqli_query($conn, $listarEmpleados);
                  while ($row = mysqli_fetch_array($listarRegistros)) { ?>
                    <tr>
                      <td><?php echo $row["id_empleado"]; ?></td>
                      <td><?php echo $row["email"]; ?></td>
                      <td><?php echo $row["nombre"]; ?></td>
                      <td><?php echo $row["apellido"]; ?></td>
                      <td><?php echo $row["edad"]; ?></td>
                      <td><?php echo $row["dni"]; ?></td>
                      <td><?php echo $row["telefono"]; ?></td>
                      <td><?php echo $row["rol"]; ?></td>
                      <td>
                        <a class="btn btn-primary" href="<?php echo BASE_URL; ?>/src/Views/modificar.php?id_empleado=<?php echo $row["id_empleado"]; ?>"><i class="bi bi-pencil-square"></i></a>
                        <a class="btn btn-danger" href="<?php echo BASE_URL; ?>/src/Controllers/eliminarEmpleado.php?id_empleado=<?php echo $row["id_empleado"]; ?>"><i class="bi bi-trash3-fill"></i></a>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
  // Footer y modal de contactos
  include_once __DIR__ . '/../Template/footer.php';
  include_once __DIR__ . '/../Components/modalContactos.php';
  ?>
  <script>
    // Validación en el cliente para deshabilitar el botón si algún campo está vacío
    document.addEventListener('DOMContentLoaded', function() {
      const campos = ['email', 'clave', 'nombre', 'apellido', 'edad', 'dni', 'telefono', 'rol'];
      const btn = document.getElementById('crearEmpleadoBtn');
      campos.forEach(id => {
        document.getElementById(id).addEventListener('input', validarCampos);
      });

      function validarCampos() {
        let vacio = false;
        campos.forEach(id => {
          const el = document.getElementById(id);
          if (!el.value.trim()) vacio = true;
        });
        btn.disabled = vacio;
      }
      validarCampos();
    });
  </script>
</body>

</html>