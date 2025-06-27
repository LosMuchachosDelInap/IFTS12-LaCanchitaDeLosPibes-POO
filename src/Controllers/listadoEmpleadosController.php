<?php
require_once __DIR__ . '/../Model/Empleado.php';
require_once __DIR__ . '/../ConectionBD/CConection.php';

$conn = (new ConectionDB())->getConnection();
$empleados = Empleado::listarTodos($conn);

// Traer roles para el select
$roles = [];
$result = $conn->query("SELECT id_roles, rol FROM roles");
while ($row = $result->fetch_assoc()) {
    $roles[] = $row;
}

// Procesar alta de empleado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crearEmpleado'])) {
    $campos = ['email', 'clave', 'nombre', 'apellido', 'edad', 'dni', 'telefono', 'rol'];
    $errorCampos = false;
    foreach ($campos as $campo) {
        if (empty($_POST[$campo])) {
            $errorCampos = true;
            break;
        }
    }
    if ($errorCampos) {
        $mensajeError = 'Debe llenar todos los campos';
    } else {
        $id_Rol = $_POST['rol'] ?? null;

        // Insertar persona
        $crearPersonaQuery = "INSERT INTO persona (apellido, nombre, edad, dni, telefono) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($crearPersonaQuery);
        $stmt->bind_param("ssiss", $_POST['apellido'], $_POST['nombre'], $_POST['edad'], $_POST['dni'], $_POST['telefono']);
        $stmt->execute();
        $idPersonaObtenido = $conn->insert_id;
        $stmt->close();

        if ($idPersonaObtenido) {
            $clave = $_POST['clave'];
            $hashed_password = password_hash($clave, PASSWORD_DEFAULT);
            $registrarPersonaQuery = "INSERT INTO usuario (id_persona, email, clave) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($registrarPersonaQuery);
            $stmt->bind_param("iss", $idPersonaObtenido, $_POST['email'], $hashed_password);
            $stmt->execute();
            $idUsuarioObtenido = $conn->insert_id;
            $stmt->close();

            if ($id_Rol) {
                $crearEmpleado = "INSERT INTO empleado (id_rol, id_persona, id_usuario) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($crearEmpleado);
                $stmt->bind_param("iii", $id_Rol, $idPersonaObtenido, $idUsuarioObtenido);
                $stmt->execute();
                $stmt->close();
            }
            $_SESSION['mensaje'] = 'Usuario creado exitosamente';
            header("Location: listadoEmpleadosController.php");
            exit;
        } else {
            $mensajeError = 'Error al crear usuario';
        }
    }
}

include __DIR__ . '/../Views/listado.php';