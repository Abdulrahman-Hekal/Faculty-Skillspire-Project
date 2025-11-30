<?php

// The parent model class
class Model
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }
}   
