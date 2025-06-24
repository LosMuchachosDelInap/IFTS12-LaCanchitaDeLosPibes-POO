<?php
// filepath: c:\xampp\htdocs\Mis_Proyectos\IFTS12-LaCanchitaDeLosPibes-POO\src\Controllers\reservarCanchaController.php

session_start();
require_once __DIR__ . '/../ConectionBD/CConection.php';
require_once __DIR__ . '/../Model/Reserva.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['id_usuario'])) {
        $_SESSION['reserva_error'] = "Debes iniciar sesión para reservar.";
        header("Location: ../Views/reservarCancha.php");
        exit;
    }

    $id_usuario = $_SESSION['id_usuario'];
    $id_cancha = $_POST['cancha'] ?? null;
    $id_fecha = $_POST['fecha'] ?? null;
    $id_precio = $_POST['precio'] ?? null;
    $id_horario = $_POST['horario'] ?? null;

    if ($id_cancha && $id_fecha && $id_precio && $id_horario) {
        $conn = (new ConectionDB())->getConnection();
        $reserva = new Reserva($id_usuario, $id_cancha, $id_fecha, $id_precio, $id_horario);
        if ($reserva->guardar($conn)) {
            $_SESSION['reserva_ok'] = "¡Reserva realizada con éxito!";
        } else {
            $_SESSION['reserva_error'] = "Error al guardar la reserva.";
        }
    } else {
        $_SESSION['reserva_error'] = "Completa todos los campos.";
    }
    header("Location: ../Views/reservarCancha.php");
    exit;
}