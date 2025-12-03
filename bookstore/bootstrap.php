<?php

require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables if .env exists
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Load configuration
$config = require __DIR__ . '/app/config.php';

// Set timezone
date_default_timezone_set($config['app']['timezone']);

// Error reporting
if ($config['app']['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define path constants
define('BASE_PATH', $config['paths']['base']);
define('PUBLIC_PATH', $config['paths']['public']);
define('VIEWS_PATH', $config['paths']['views']);
define('STORAGE_PATH', $config['paths']['storage']);

// Define URL constants
define('BASE_URL', $config['urls']['base']);
define('ASSETS_URL', $config['urls']['assets']);

// Database connection
function getDbConnection() {
    static $pdo;
    
    if ($pdo === null) {
        $config = require __DIR__ . '/app/config.php';
        $db = $config['database'];
        
        try {
            $dsn = "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $pdo = new PDO($dsn, $db['username'], $db['password'], $options);
        } catch (PDOException $e) {
            // Log error and show user-friendly message
            error_log('Database connection failed: ' . $e->getMessage());
            die('Could not connect to the database. Please try again later.');
        }
    }
    
    return $pdo;
}

// Helper functions
function asset($path) {
    return rtrim(ASSETS_URL, '/') . '/' . ltrim($path, '/');
}

function url($path = '') {
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function view($path, $data = []) {
    extract($data);
    $viewFile = VIEWS_PATH . '/' . str_replace('.', '/', $path) . '.php';
    
    if (!file_exists($viewFile)) {
        throw new Exception("View [{$path}] not found.");
    }
    
    ob_start();
    include $viewFile;
    return ob_get_clean();
}
