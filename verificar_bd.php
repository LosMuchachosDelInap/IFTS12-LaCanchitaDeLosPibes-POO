<?php

require_once __DIR__ . '/src/ConectionBD/CConection.php';

// Configuración de errores para desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conectarDB = new ConectionDB();
$conn = $conectarDB->getConnection();

echo "=== VERIFICACIÓN DE BASE DE DATOS ===<br><br>";

// 1. Verificar tabla usuario
echo "<strong>1. Verificando tabla usuario:</strong><br>";
$result = $conn->query("SELECT id_usuario, email, id_persona FROM usuario LIMIT 5");
if ($result && $result->num_rows > 0) {
    echo "✓ Tabla usuario encontrada<br>";
    while ($row = $result->fetch_assoc()) {
        echo "&nbsp;&nbsp;- Usuario ID {$row['id_usuario']}: {$row['email']}<br>";
    }
} else {
    echo "✗ No se encontraron usuarios o hay un error<br>";
}

// 2. Verificar tabla persona
echo "<br><strong>2. Verificando tabla persona:</strong><br>";
$result = $conn->query("SELECT id_persona, nombre, apellido FROM persona LIMIT 5");
if ($result && $result->num_rows > 0) {
    echo "✓ Tabla persona encontrada<br>";
    while ($row = $result->fetch_assoc()) {
        echo "&nbsp;&nbsp;- Persona ID {$row['id_persona']}: {$row['nombre']} {$row['apellido']}<br>";
    }
} else {
    echo "✗ No se encontraron personas o hay un error<br>";
}

// 3. Verificar tabla cancha
echo "<br><strong>3. Verificando tabla cancha:</strong><br>";
$result = $conn->query("SELECT id_cancha, nombreCancha, precio FROM cancha WHERE habilitado = 1 AND cancelado = 0");
if ($result && $result->num_rows > 0) {
    echo "✓ Tabla cancha encontrada<br>";
    while ($row = $result->fetch_assoc()) {
        echo "&nbsp;&nbsp;- Cancha ID {$row['id_cancha']}: {$row['nombreCancha']} (\${$row['precio']})<br>";
    }
} else {
    echo "✗ No se encontraron canchas habilitadas<br>";
}

// 4. Verificar tabla horario
echo "<br><strong>4. Verificando tabla horario:</strong><br>";
$result = $conn->query("SELECT id_horario, horario FROM horario LIMIT 10");
if ($result && $result->num_rows > 0) {
    echo "✓ Tabla horario encontrada<br>";
    while ($row = $result->fetch_assoc()) {
        echo "&nbsp;&nbsp;- Horario ID {$row['id_horario']}: {$row['horario']}<br>";
    }
} else {
    echo "✗ No se encontraron horarios<br>";
}

// 5. Verificar tabla fecha
echo "<br><strong>5. Verificando tabla fecha:</strong><br>";
$result = $conn->query("SELECT id_fecha, fecha FROM fecha ORDER BY fecha DESC LIMIT 5");
if ($result && $result->num_rows > 0) {
    echo "✓ Tabla fecha encontrada<br>";
    while ($row = $result->fetch_assoc()) {
        echo "&nbsp;&nbsp;- Fecha ID {$row['id_fecha']}: {$row['fecha']}<br>";
    }
} else {
    echo "✗ No se encontraron fechas<br>";
}

// 6. Verificar relación usuario-persona con emails
echo "<br><strong>6. Verificando relación usuario-persona:</strong><br>";
$sql = "SELECT u.id_usuario, u.email, p.nombre, p.apellido 
        FROM usuario u 
        JOIN persona p ON u.id_persona = p.id_persona 
        WHERE u.email IS NOT NULL AND u.email != '' 
        LIMIT 5";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    echo "✓ Relación usuario-persona correcta<br>";
    while ($row = $result->fetch_assoc()) {
        echo "&nbsp;&nbsp;- {$row['nombre']} {$row['apellido']} ({$row['email']})<br>";
    }
} else {
    echo "✗ No se encontraron usuarios con emails válidos<br>";
}

// 7. Verificar tabla reserva
echo "<br><strong>7. Verificando tabla reserva:</strong><br>";
$result = $conn->query("SELECT COUNT(*) as total FROM reserva");
if ($result) {
    $row = $result->fetch_assoc();
    echo "✓ Tabla reserva encontrada - Total reservas: {$row['total']}<br>";
} else {
    echo "✗ Error al acceder a tabla reserva<br>";
}

echo "<br><strong>=== FIN VERIFICACIÓN ===</strong>";

?>
