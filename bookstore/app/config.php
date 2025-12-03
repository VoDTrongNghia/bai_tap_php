<?php

declare(strict_types=1);

// Application Configuration
return [
    // Application Info
    'app' => [
        'name' => 'BookStore',
        'url' => 'http://localhost/bookstore',
        'timezone' => 'Asia/Ho_Chi_Minh',
        'debug' => true,
    ],
    
    // Database Configuration
    'database' => [
        'host' => 'localhost',
        'name' => 'ban_sach',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
    ],
    
    // Paths
    'paths' => [
        'base' => dirname(__DIR__),
        'public' => dirname(__DIR__) . '/public',
        'views' => dirname(__DIR__) . '/resources/views',
        'storage' => dirname(__DIR__) . '/storage',
        'cache' => dirname(__DIR__) . '/storage/cache',
        'logs' => dirname(__DIR__) . '/storage/logs',
    ],
    
    // URLs
    'urls' => [
        'base' => 'http://localhost/bookstore',
        'assets' => 'http://localhost/bookstore/public',
    ],
];
