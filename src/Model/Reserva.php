<?php
// filepath: c:\xampp\htdocs\Mis_Proyectos\IFTS12-LaCanchitaDeLosPibes-POO\src\Model\Reserva.php

class Reserva
{
    private $id_reserva;
    private $id_usuario;
    private $id_cancha;
    private $id_fecha;
    private $id_horario;

    public function __construct($id_usuario, $id_cancha, $id_fecha, $id_horario, $id_reserva = null)
    {
        $this->id_usuario = $id_usuario;
        $this->id_cancha = $id_cancha;
        $this->id_fecha = $id_fecha;
        $this->id_horario = $id_horario;
        $this->id_reserva = $id_reserva;
    }

    public function guardar($conn)
    {
        $stmt = $conn->prepare("INSERT INTO reserva (id_usuario, id_cancha, id_fecha, id_horario) VALUES ( ?, ?, ?, ?)");
        $stmt->bind_param("iiii", $this->id_usuario, $this->id_cancha, $this->id_fecha, $this->id_horario);
        return $stmt->execute();
    }
   
    public static function listarTodas($conn, $fecha_desde = null, $fecha_hasta = null)
    {
        $sql = "SELECT r.*, c.nombreCancha, c.precio, h.horario, f.fecha, u.email, p.nombre, p.apellido, p.telefono
                FROM reserva r
                JOIN cancha c ON r.id_cancha = c.id_cancha
                JOIN horario h ON r.id_horario = h.id_horario
                JOIN fecha f ON r.id_fecha = f.id_fecha
                JOIN usuario u ON r.id_usuario = u.id_usuario
                JOIN persona p ON u.id_persona = p.id_persona";
        
        // Agregar filtros de fecha si se proporcionan
        $whereConditions = [];
        $params = [];
        $types = '';
        
        if ($fecha_desde) {
            $whereConditions[] = "f.fecha >= ?";
            $params[] = $fecha_desde;
            $types .= 's';
        }
        
        if ($fecha_hasta) {
            $whereConditions[] = "f.fecha <= ?";
            $params[] = $fecha_hasta;
            $types .= 's';
        }
        
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(' AND ', $whereConditions);
        }
        
        $sql .= " ORDER BY f.fecha DESC, h.horario DESC";
        
        if (!empty($params)) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query($sql);
        }
        
        $reservas = [];
        while ($row = $result->fetch_assoc()) {
            $reservas[] = $row;
        }
        return $reservas;
    }
}
