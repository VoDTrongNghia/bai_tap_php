<?php
require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h3>Kiểm tra hình ảnh trong database:</h3>";
    $stmt = $pdo->query("SELECT id, ten_sach, anh_bia FROM sach ORDER BY id DESC LIMIT 5");
    echo "<table border='1'><tr><th>ID</th><th>Tên sách</th><th>Ảnh trong DB</th><th>Trạng thái file</th></tr>";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $imagePath = $row['anh_bia'];
        $fullPath = __DIR__ . '/public/' . $imagePath;
        $status = file_exists($fullPath) ? 'Tồn tại' : 'Không tồn tại';
        
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>" . htmlspecialchars($row['ten_sach']) . "</td>";
        echo "<td>" . htmlspecialchars($imagePath) . "</td>";
        echo "<td>$status</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>Thư mục uploads:</h3>";
    $uploadDir = __DIR__ . '/public/assets/images/books/';
    if (is_dir($uploadDir)) {
        echo "Thư mục tồn tại: $uploadDir<br>";
        echo "Phân quyền: " . substr(sprintf('%o', fileperms($uploadDir)), -4) . "<br>";
        
        $files = scandir($uploadDir);
        echo "Files trong thư mục:<br>";
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                echo "- $file<br>";
            }
        }
    } else {
        echo "Thư mục không tồn tại: $uploadDir<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
