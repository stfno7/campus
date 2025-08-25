<?php
// config/database.php

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() { /** Config para la bd. Datos de conexión */
        $host = 'localhost';
        $dbname = 'campus_educativo';
        $username = 'root';
        $password = '';

        try { /** única conexión PDO database */
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            die();
        }
    }
    /** Estanciar clase MYSQL */
    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}