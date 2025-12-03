<?php
/**
 * Logout Page - Redirect to route handler
 * This file redirects to the proper logout route handled by AuthController
 */

// Start session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Load config
require_once __DIR__ . '/../config.php';

// Redirect to logout route (handled by AuthController)
header('Location: ' . BASE_PATH . 'logout');
exit;
?>

