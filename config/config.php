<?php

// Show errors during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database Configuration
const DB_HOST = 'localhost';
const DB_PORT = '3307';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'skillspire';

// Base URL
const BASE_URL = 'http://localhost/skillspire';

// App Root
define('APPROOT', dirname(dirname(__FILE__)));

// Start Session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
