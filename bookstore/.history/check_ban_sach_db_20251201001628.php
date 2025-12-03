<?php
require_once __DIR__ . '/config.php';

try {
    // Kết nối đến database ban_sach
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=ban_sach;charset=" . DB_CHARSET, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Các bảng trong database 'ban_sach':</h2>\n";
    
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
        
        // Kiểm tra các bảng có thể chứa sách
        $possibleBookTables = ['sach', 'books', 'ban_sach', 'book', 'san_pham'];
        $foundBookTable = null;
        
        foreach ($possibleBookTables as $table) {
            if (in_array($table, $tables)) {
                $foundBookTable = $table;
                break;
            }
        }
        
        if ($foundBookTable) {
            echo "<h2>Cấu trúc bảng '$foundBookTable':</h2>\n";
            $stmt = $pdo->prepare("DESCRIBE $foundBookTable");
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
            echo "<h2>Mẫu dữ liệu trong bảng '$foundBookTable' (5 dòng đầu):</h2>\n";
            $stmt = $pdo->prepare("SELECT * FROM $foundBookTable LIMIT 5");
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
                
                echo "<h2>Tổng số sách trong bảng '$foundBookTable':</h2>\n";
                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM $foundBookTable");
                $stmt->execute();
                $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                echo "<p>Tổng số: <strong>" . number_format($total) . "</strong> sách</p>\n";
                
            } else {
                echo "<p style='color: orange;'>⚠️ Bảng '$foundBookTable' không có dữ liệu</p>\n";
            }
        } else {
            echo "<p style='color: orange;'>⚠️ Không tìm thấy bảng chứa sách trong database 'ban_sach'</p>\n";
            echo "<h2>Chi tiết tất cả các bảng:</h2>\n";
            foreach ($tables as $table) {
                echo "<h3>Bảng: " . htmlspecialchars($table) . "</h3>\n";
                $stmt = $pdo->prepare("DESCRIBE $table");
                $stmt->execute();
                $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>\n";
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
            }
        }
    } else {
        echo "<p style='color: orange;'>⚠️ Database 'ban_sach' không có bảng nào</p>\n";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Lỗi kết nối database: " . htmlspecialchars($e->getMessage()) . "</p>\n";
}
?>
