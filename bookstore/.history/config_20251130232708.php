<?php
// Thông tin ứng dụng
define('APP_NAME', 'BookStore');
define('APP_DEBUG', true);

// Đường dẫn cơ bản
define('BASE_PATH', __DIR__ . '/');
define('PUBLIC_PATH', BASE_PATH . 'public/');
define('APP_PATH', BASE_PATH . 'app/');
define('VIEWS_PATH', BASE_PATH . 'resources/views/');
define('STORAGE_PATH', BASE_PATH . 'storage/');

// URL cơ sở
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
define('BASE_URL', $protocol . $host . rtrim($scriptName, '/') . '/');

// Cấu hình cơ sở dữ liệu
define('DB_HOST', 'localhost');
define('DB_NAME', 'ban_sach');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Cấu hình session
define('SESSION_LIFETIME', 86400); // 24 giờ
define('SESSION_NAME', 'bookstore_session');

// Báo lỗi
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Múi giờ
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Hàm helper để lấy URL đầy đủ
function url($path = '') {
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

// Hàm helper để lấy URL của asset
function asset($path) {
    return url('public/' . ltrim($path, '/'));
}

// Bắt đầu session nếu chưa bắt đầu
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'name' => SESSION_NAME,
        'cookie_lifetime' => SESSION_LIFETIME,
    ]);
}