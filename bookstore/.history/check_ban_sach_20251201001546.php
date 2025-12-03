<?php
require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Cấu trúc bảng ban_sach:</h2>\n";
    
    // Kiểm tra xem bảng ban_sach có tồn tại không
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'ban_sach'");
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Bảng ban_sach tồn tại</p>\n";
        
        // Lấy cấu trúc bảng
        $stmt = $pdo->prepare("DESCRIBE ban_sach");
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table border='1' style='border-collapse: collapse; margin: 20px 0;'>\n";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>\n";
        
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
            echo "</tr>\n";
        }
        
        echo "</table>\n";
        
        // Hiển thị một vài mẫu dữ liệu
        echo "<h2>Mẫu dữ liệu trong bảng ban_sach (5 dòng đầu):</h2>\n";
        $stmt = $pdo->prepare("SELECT * FROM ban_sach LIMIT 5");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($rows) > 0) {
            echo "<table border='1' style='border-collapse: collapse; margin: 20px 0;'>\n";
            echo "<tr>";
            foreach (array_keys($rows[0]) as $key) {
                echo "<th>" . htmlspecialchars($key) . "</th>";
            }
            echo "</tr>\n";
            
            foreach ($rows as $row) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value ?? '') . "</td>";
                }
                echo "</tr>\n";
            }
            echo "</table>\n";
        } else {
            echo "<p style='color: orange;'>⚠️ Bảng ban_sach không có dữ liệu</p>\n";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Bảng ban_sach không tồn tại</p>\n";
        
        // Hiển thị các bảng có sẵn
        echo "<h2>Các bảng có sẵn trong database:</h2>\n";
        $stmt = $pdo->prepare("SHOW TABLES");
        $stmt->execute();
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "<ul>\n";
        foreach ($tables as $table) {
            echo "<li>" . htmlspecialchars($table) . "</li>\n";
        }
        echo "</ul>\n";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Lỗi kết nối database: " . htmlspecialchars($e->getMessage()) . "</p>\n";
}
?>
