<?php
require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h3>Kiểm tra cấu trúc bảng sách:</h3>";
    
    // Kiểm tra bảng sach
    $stmt = $pdo->query("DESCRIBE sach");
    echo "<h4>Bảng sach:</h4>";
    echo "<table border='1'><tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Kiểm tra bảng books
    $stmt = $pdo->query("DESCRIBE books");
    echo "<h4>Bảng books:</h4>";
    echo "<table border='1'><tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Kiểm tra dữ liệu mẫu
    echo "<h3>Dữ liệu mẫu:</h3>";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM sach");
    $sachCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM books");
    $booksCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo "<p>Số lượng trong bảng sach: " . $sachCount . "</p>";
    echo "<p>Số lượng trong bảng books: " . $booksCount . "</p>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
