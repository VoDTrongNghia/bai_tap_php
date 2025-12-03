<?php
/**
 * Login Page - Redirect to route handler
 * This file redirects to the proper login route handled by AuthController
 * 
 * NOTE: This file is kept for backward compatibility.
 * The actual login is handled by AuthController via /login route.
 */

// Start session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Load config
require_once __DIR__ . '/../config.php';

// If already logged in as admin, redirect to admin dashboard
if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || ($_SESSION['vai_tro'] ?? '') === 'admin' || ($_SESSION['vai_tro'] ?? '') === 'quan_tri_vien')) {
    header('Location: ' . BASE_PATH . 'admin');
    exit;
}

// If already logged in as user, redirect to home
if (isset($_SESSION['user'])) {
    header('Location: ' . BASE_PATH);
    exit;
}

// Redirect to login route (handled by AuthController)
header('Location: ' . BASE_PATH . 'login');
exit;
