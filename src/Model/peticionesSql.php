<?php
// DECLARACIONES DE VARIABLES - ADJUNTO EL CAMPO DE LA TABLA (EXACTAMENTE COMO FIGURA EN LA TABLA) A LA VARIABLE
// TABLA USUARIO
$idUsuario = $_POST['id_usuario'] ?? null;
$email = $_POST['email'] ?? null;
$clave = $_POST['clave'] ?? null;
// TABLA PERSONA
$idPersona = $_POST['id_persona'] ?? null;
$apellido = $_POST['apellido'] ?? null;
$nombre = $_POST['nombre'] ?? null;
$telefono = $_POST['telefono'] ?? null;
$dni = $_POST['dni'] ?? null;
$edad = $_POST['edad'] ?? null;
// TABLA EMPLEADO
$idEmpleado = $_POST['id_empleado'] ?? null;
//TABLA ROLES
$idRol = $_POST['id_roles'] ?? null;
$rol = $_POST['rol'] ?? null;
// TABLA CANCHA
$idCancha = $_POST['id_cancha'] ?? null;
$nomnbreCancha = $_POST['nombreCancha'] ?? null;
// TABLA FECHA
$idFecha = $_POST['id_fecha'] ?? null;
$fecha = $_POST['fecha'] ?? null;
// TABLA HORARIO 
$idHorario = $_POST['id_horario'] ?? null;
$hora = $_POST['hora'] ?? null;
// TABLA PRECIO
$idPrecio = $_POST['id_precio'] ?? null;
$precio = $_POST['precio'] ?? null;
// TABLA RESERVA - SE RELACIONAN TODAS LA TABLAS
$idReserva = $_POST['id_reserva'] ?? null;
$idUsuario = $_POST['id_usuario'] ?? null;
$idCancha = $_POST['id_cancha'] ?? null;
$idFecha = $_POST['id_fecha'] ?? null;
$idPrecio = $_POST['id_precio'] ?? null;
$idHorario = $_POST['id_horario'] ?? null;

//-------------------------------------SENTENCIAS----------------------------------------------------------------------------------------------------------------//
// LOGIN //
$login = "SELECT email,clave FROM usuario WHERE email='$email' AND clave='$clave' AND habilitado=1 AND cancelado=0";
// CREAR USUARIO //
$crearUsuarioQuery = "INSERT INTO usuario (id_persona, email,clave) VALUES (?,?,?) ";
// CREAR PERSONA
$crearPersonaQuery = "INSERT INTO persona (apellido,nombre,edad,dni,telefono) VALUES (?,?,?,?,?)";
// CREAR EMPLEADO
$crearEmpleadoQuery = "INSERT INTO empleado (id_rol,id_persona,id_usuario) VALUES (?,?,?)";
// LISTAR USUARIOS
$listarUsuarios = "SELECT usuario.id_usuario, usuario.email, usuario.clave, empleado.id_empleado 
FROM usuario 
INNER JOIN empleado ON usuario.id_empleado = empleado.id_empleado
WHERE usuario.habilitado=1 AND usuario.cancelado = 0 ORDER BY usuario.id_usuario DESC";

// LISTAR EMPLEADOS
$listarEmpleados = "SELECT persona.id_persona, empleado.id_empleado, persona.edad, persona.nombre, persona.apellido, persona.dni, roles.rol, usuario.email, persona.telefono
FROM empleado
INNER JOIN persona ON empleado.id_persona = persona.id_persona 
INNER JOIN roles ON empleado.id_rol  = roles.id_roles 
INNER JOIN usuario ON empleado.id_usuario = usuario.id_usuario
WHERE empleado.habilitado = 1 AND empleado.cancelado = 0 ORDER BY empleado.id_empleado ASC ";
// LISTAR CARGO
$listarRol = "SELECT id_roles,rol FROM roles WHERE habilitado=1 AND cancelado=0";
// EDITAR EMPLEADOS
$listarEmpleado = "SELECT empleado.id_empleado, empleado.id_persona, empleado.id_rol, empleado.id_usuario, persona.nombre, persona.apellido, persona.edad, persona.dni, roles.rol, usuario.email, usuario.clave, persona.telefono
FROM empleado
INNER JOIN persona ON empleado.id_persona = persona.id_persona 
INNER JOIN roles ON empleado.id_rol  = roles.id_roles 
INNER JOIN usuario ON empleado.id_usuario = usuario.id_usuario
WHERE empleado.id_empleado='$idEmpleado' AND empleado.habilitado=1 AND empleado.cancelado=0";
// ELIMINAR UN EMPLEADO
$eliminarEmpleado = "UPDATE empleado SET habilitado=0, cancelado=1 WHERE id_empleado=?";
