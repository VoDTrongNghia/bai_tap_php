<?php

declare(strict_types=1);

// Ensure ROOT_DIR is available when bootstrap is loaded directly from CLI scripts
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', dirname(__DIR__));
}

// Load global config if it exists (defines BASE_PATH, DB_* constants, etc.)
$configPath = ROOT_DIR . DIRECTORY_SEPARATOR . 'config.php';
if (is_readable($configPath)) {
    require_once $configPath;
}

// Set default timezone to avoid PHP warnings when working with dates
if (!ini_get('date.timezone')) {
    date_default_timezone_set('Asia/Ho_Chi_Minh');
}

// Start the session once for the entire request lifecycle
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Simple PSR-4 style autoloader for the `App` namespace
spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return; // Not part of our namespace
    }

    $relativeClass = substr($class, strlen($prefix));
    $relativePath = str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass);
    $file = ROOT_DIR . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $relativePath . '.php';

    if (is_readable($file)) {
        require_once $file;
    }
});
