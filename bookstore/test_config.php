<?php
require_once 'config.php';

echo "<h1>Kiểm tra cấu hình</h1>";

// Kiểm tra các hằng số
echo "<h2>Các hằng số:</h2>";
echo "APP_NAME: " . APP_NAME . "<br>";
echo "BASE_URL: " . BASE_URL . "<br>";
echo "BASE_PATH: " . BASE_PATH . "<br>";
echo "VIEWS_PATH: " . VIEWS_PATH . "<br>";
echo "PUBLIC_PATH: " . PUBLIC_PATH . "<br>";

// Kiểm tra kết nối database
echo "<h2>Kiểm tra kết nối database:</h2>";
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS
    );
    echo "Kết nối database thành công!<br>";
    
    // Kiểm tra bảng sách
    $stmt = $pdo->query("SHOW TABLES LIKE 'sach'");
    echo "Bảng sách: " . ($stmt->rowCount() > 0 ? "Tồn tại" : "Không tồn tại") . "<br>";
    
} catch (PDOException $e) {
    echo "Lỗi kết nối database: " . $e->getMessage() . "<br>";
}

// Kiểm tra thư mục views
echo "<h2>Kiểm tra thư mục views:</h2>";
$viewsDir = VIEWS_PATH;
if (is_dir($viewsDir)) {
    echo "Thư mục views tồn tại<br>";
    echo "Đường dẫn: " . realpath($viewsDir) . "<br>";
    
    // Kiểm tra một số file view quan trọng
    $importantViews = [
        'pages/home.php',
        'layout/header.php',
        'layout/footer.php',
        'admin/dashboard.php'
    ];
    
    echo "<h3>Kiểm tra các file view quan trọng:</h3>";
    foreach ($importantViews as $view) {
        $viewPath = $viewsDir . $view;
        echo $view . ": " . (file_exists($viewPath) ? "Tồn tại" : "Không tồn tại") . "<br>";
    }
} else {
    echo "Thư mục views không tồn tại!<br>";
}

// Kiểm tra quyền ghi
echo "<h2>Kiểm tra quyền ghi:</h2>";
$writableDirs = [
    'storage' => STORAGE_PATH,
    'cache' => STORAGE_PATH . 'cache/',
    'logs' => STORAGE_PATH . 'logs/'
];

foreach ($writableDirs as $name => $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    echo "$name: " . (is_writable($dir) ? "Có quyền ghi" : "Không có quyền ghi") . "<br>";
}