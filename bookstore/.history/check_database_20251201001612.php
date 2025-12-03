<?php
require_once __DIR__ . '/config.php';

try {
    // Kết nối không có database
    $pdo = new PDO("mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Các database có sẵn:</h2>\n";
    
    // Lấy danh sách database
    $stmt = $pdo->prepare("SHOW DATABASES");
    $stmt->execute();
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<ul>\n";
    foreach ($databases as $db) {
        echo "<li>" . htmlspecialchars($db) . "</li>\n";
    }
    echo "</ul>\n";
    
    // Tạo database bookstore nếu chưa có
    if (!in_array('bookstore', $databases)) {
        echo "<h2>Đang tạo database 'bookstore'...</h2>\n";
        $pdo->exec("CREATE DATABASE bookstore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "<p style='color: green;'>✅ Database 'bookstore' đã được tạo thành công!</p>\n";
    } else {
        echo "<p style='color: green;'>✅ Database 'bookstore' đã tồn tại</p>\n";
    }
    
    // Kết nối đến database bookstore
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Các bảng trong database 'bookstore':</h2>\n";
    
    // Lấy danh sách bảng
    $stmt = $pdo->prepare("SHOW TABLES");
    $stmt->execute();
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "<ul>\n";
        foreach ($tables as $table) {
            echo "<li>" . htmlspecialchars($table) . "</li>\n";
        }
        echo "</ul>\n";
        
        // Kiểm tra bảng ban_sach
        if (in_array('ban_sach', $tables)) {
            echo "<h2>Cấu trúc bảng ban_sach:</h2>\n";
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
            
            // Hiển thị mẫu dữ liệu
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
            echo "<p style='color: orange;'>⚠️ Bảng ban_sach không tồn tại trong database 'bookstore'</p>\n";
        }
    } else {
        echo "<p style='color: orange;'>⚠️ Database 'bookstore' không có bảng nào</p>\n";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Lỗi: " . htmlspecialchars($e->getMessage()) . "</p>\n";
}
?>
