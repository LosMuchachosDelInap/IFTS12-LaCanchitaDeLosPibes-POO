<?php
// filepath: c:\xampp\htdocs\Mis_Proyectos\IFTS12-LaCanchitaDeLosPibes-POO\src\Model\Reserva.php

class Reserva {
    private $id_reserva;
    private $id_usuario;
    private $id_cancha;
    private $id_fecha;
    private $id_horario;

    public function __construct($id_usuario, $id_cancha, $id_fecha, $id_horario, $id_reserva = null) {
        $this->id_usuario = $id_usuario;
        $this->id_cancha = $id_cancha;
        $this->id_fecha = $id_fecha;
        $this->id_horario = $id_horario;
        $this->id_reserva = $id_reserva;
    }

    public function guardar($conn) {
        $stmt = $conn->prepare("INSERT INTO reserva (id_usuario, id_cancha, id_fecha, id_horario) VALUES ( ?, ?, ?, ?)");
        $stmt->bind_param("iiii", $this->id_usuario, $this->id_cancha, $this->id_fecha, $this->id_horario);
        return $stmt->execute();
    }

    // Puedes agregar métodos para buscar reservas, cancelar, etc.
}