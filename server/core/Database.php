<?php

class Database
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

        // Check for connection error
        if ($this->conn->connect_error) {
            throw new Exception("Database Connection Failed: " . $this->conn->connect_error);
        }
    }
    // Get the Connection
    public static function getConnection() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}