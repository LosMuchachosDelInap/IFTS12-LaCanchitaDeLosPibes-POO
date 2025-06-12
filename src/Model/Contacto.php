<?php
class Contacto {
    private $email;
    private $mensaje;

    public function __construct($email, $mensaje) {
        $this->email = $email;
        $this->mensaje = $mensaje;
    }

    public function getEmail() { return $this->email; }
    public function getMensaje() { return $this->mensaje; }

    // Si quieres guardar en la base de datos, puedes agregar un método aquí
}