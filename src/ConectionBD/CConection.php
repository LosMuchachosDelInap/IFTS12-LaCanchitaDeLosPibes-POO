<?php
// filepath: c:\xampp\htdocs\Mis proyectos\IFTS12-LaCanchitaDeLosPibes\src\ConectionBD\CConection.php

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

class ConectionDB
{
    private $host;
    private $username;
    private $password;
    private $dbname;
    private $conn;
    private $charset;

    public function __construct()
    {
        // Cargar variables de entorno solo una vez
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->host     = $_ENV['DB_HOST'];
        $this->username = $_ENV['DB_USERNAME'];
        $this->password = $_ENV['DB_PASSWORD'];
        $this->dbname   = $_ENV['DB_NAME'];
        $this->charset  = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

        $this->conn = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->dbname
        );

        $this->conn->set_charset($this->charset);

        if ($this->conn->connect_error) {
            echo "<script>console.error('Error de conexión: " . addslashes($this->conn->connect_error) . "');</script>";
        } else {
            echo "<script>console.log('Conexión exitosa a la base de datos');</script>";
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
