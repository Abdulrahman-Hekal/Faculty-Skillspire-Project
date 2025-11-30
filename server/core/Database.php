<?php

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

        // Check for connection error
        if ($this->connection->connect_error) {
            throw new Exception("Database Connection Failed: " . $this->connection->connect_error);
        }
    }

    // Get the Connection
    public static function getConnection() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
    
    // Prevent cloning
    private function __clone() {}

    // Prevent unserializing
    public function __wakeup() {
        throw new Exception("Cannot unserialize a singleton.");
    }
}