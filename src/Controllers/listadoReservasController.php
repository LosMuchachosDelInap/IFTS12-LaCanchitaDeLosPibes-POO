<?php

require_once __DIR__ . '/../Model/Reserva.php';
require_once __DIR__ . '/../ConectionBD/CConection.php';

$conn = (new ConectionDB())->getConnection();

// Obtener filtros de fecha si existen
$fecha_desde = $_GET['fecha_desde'] ?? null;
$fecha_hasta = $_GET['fecha_hasta'] ?? null;

// Llama al método del modelo para traer todas las reservas con filtros opcionales
$reservas = Reserva::listarTodas($conn, $fecha_desde, $fecha_hasta);

include __DIR__ . '/../Views/listadoReservas.php';