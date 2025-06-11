<?php
class Usuario {
    private $id_usuario;
    private $email;
    private $clave;
    private $id_persona;

    public function __construct($email, $clave, $id_persona, $id_usuario = null, $hash = true) {
        $this->id_usuario = $id_usuario;
        $this->email = $email;
        // Si $hash es true, hashea la clave (para nuevos usuarios); si es false, la clave ya viene hasheada desde la BD
        $this->clave = $hash ? password_hash($clave, PASSWORD_DEFAULT) : $clave;
        $this->id_persona = $id_persona;
    }

    // Getters
    public function getId() { return $this->id_usuario; }
    public function getEmail() { return $this->email; }
    public function getClave() { return $this->clave; }
    public function getIdPersona() { return $this->id_persona; }

    // Setters
    public function setEmail($email) { $this->email = $email; }
    public function setClave($clave) { $this->clave = password_hash($clave, PASSWORD_DEFAULT); }
    public function setIdPersona($id_persona) { $this->id_persona = $id_persona; }

    // Guardar usuario en la base de datos
    public function guardar($conn) {
        $stmt = $conn->prepare("INSERT INTO usuario (email, clave, id_persona) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $this->email, $this->clave, $this->id_persona);
        if ($stmt->execute()) {
            $this->id_usuario = $conn->insert_id;
            return true;
        }
        return false;
    }

    // Buscar usuario por ID
    public static function buscarPorId($conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($fila = $resultado->fetch_assoc()) {
            // $hash = false porque la clave ya viene hasheada de la BD
            return new Usuario($fila['email'], $fila['clave'], $fila['id_persona'], $fila['id_usuario'], false);
        }
        return null;
    }

    // Verificar clave (para login)
    public function verificarClave($clavePlano) {
        return password_verify($clavePlano, $this->clave);
    }

    public function actualizar($conn) {
        $stmt = $conn->prepare("UPDATE usuario SET email = ?, clave = ?, id_persona = ? WHERE id_usuario = ?");
        $stmt->bind_param("ssii", $this->email, $this->clave, $this->id_persona, $this->id_usuario);
        return $stmt->execute();
    }

    public function eliminar($conn) {
        $stmt = $conn->prepare("DELETE FROM usuario WHERE id_usuario = ?");
        $stmt->bind_param("i", $this->id_usuario);
        return $stmt->execute();
    }
}