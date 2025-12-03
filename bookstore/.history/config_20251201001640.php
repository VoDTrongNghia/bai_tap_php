<?php
// Thông tin ứng dụng
define('APP_NAME', 'BookStore');
define('APP_DEBUG', true);
define('APP_ENV', 'development'); // development, testing, production

// Đường dẫn cơ sở
define('BASE_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('PUBLIC_PATH', BASE_PATH . 'public' . DIRECTORY_SEPARATOR);
define('APP_PATH', BASE_PATH . 'app' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', BASE_PATH . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);
define('STORAGE_PATH', BASE_PATH . 'storage' . DIRECTORY_SEPARATOR);
define('CONFIG_PATH', APP_PATH . 'config' . DIRECTORY_SEPARATOR);

// URL cơ sở
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = dirname($_SERVER['SCRIPT_NAME']);

// Xử lý trường hợp chạy từ thư mục gốc hoặc public
if (strpos($scriptName, 'public') !== false) {
    $baseUrl = rtrim($protocol . $host . $scriptName, '/');
} else {
    $baseUrl = $protocol . $host . (($scriptName !== '/' && $scriptName !== '\\') ? $scriptName : '') . '/bookstore/public';
}

// Định nghĩa URL cơ sở
define('BASE_URL', rtrim($baseUrl, '/') . '/');
define('ASSETS_URL', BASE_URL . 'assets/');

// Cấu hình database
define('DB_HOST', 'localhost');
define('DB_NAME', 'ban_sach');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Cấu hình session
define('SESSION_LIFETIME', 86400); // 24 hours
define('SESSION_NAME', 'bookstore_session');

// Cấu hình upload
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Tự động tạo thư mục storage nếu chưa tồn tại
$storageDirs = [
    'logs',
    'cache',
    'sessions',
    'uploads/books',
    'uploads/users'
];

foreach ($storageDirs as $dir) {
    $path = STORAGE_PATH . $dir;
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

// Thiết lập error reporting
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Thiết lập múi giờ
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Hàm helper
if (!function_exists('asset')) {
    function asset($path) {
        return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    function url($path = '') {
        return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return STORAGE_PATH . ltrim($path, '/\\');
    }
}

// Khởi tạo session
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}