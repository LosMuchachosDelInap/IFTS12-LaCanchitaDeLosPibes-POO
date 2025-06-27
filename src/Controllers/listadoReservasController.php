<?php

require_once __DIR__ . '/../Model/Reserva.php';
require_once __DIR__ . '/../ConectionBD/CConection.php';

$conn = (new ConectionDB())->getConnection();
// Llama al método del modelo para traer todas las reservas.
$reservas = Reserva::listarTodas($conn);

include __DIR__ . '/../Views/listadoReservas.php';