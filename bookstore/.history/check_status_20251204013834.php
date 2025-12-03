<?php
require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h3>Trạng thái hiện tại trong database:</h3>";
    $stmt = $pdo->query("SELECT DISTINCT trang_thai FROM don_hang");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<p>- " . htmlspecialchars($row['trang_thai']) . "</p>";
    }
    
    echo "<h3>Dữ liệu mẫu:</h3>";
    $stmt = $pdo->query("SELECT id, trang_thai, status FROM don_hang LIMIT 5");
    echo "<table border='1'><tr><th>ID</th><th>trang_thai</th><th>status</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['trang_thai']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
