<?php

include "header.php";
?>

<h1>Dashboard Admin</h1>
<p>Chào mừng bạn đến với trang quản trị.</p>

<?php
// Hiển thị danh sách sách
$sql = "SELECT * FROM books"; // giả sử bảng sách tên 'books'
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Tên sách</th><th>Tác giả</th><th>Giá</th><th>Hành động</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".$row['id']."</td>";
        echo "<td>".htmlspecialchars($row['title'])."</td>";
        echo "<td>".htmlspecialchars($row['author'])."</td>";
        echo "<td>".$row['price']."</td>";
        echo "<td>
                <a href='edit_book.php?id=".$row['id']."'>Sửa</a> | 
                <a href='delete_book.php?id=".$row['id']."' onclick='return confirm(\"Bạn có chắc muốn xóa?\");'>Xóa</a>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Chưa có sách nào trong cơ sở dữ liệu.</p>";
}
include "footer.php";

require_once "../config.php";
require_once "../includes/auth.php"; // Nếu file này check login

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền truy cập!";
    exit;
}

echo "<h1>Trang Admin</h1>";
echo "<p>Xin chào, " . htmlspecialchars($_SESSION['username']) . "!</p>";
echo "<a href='../logout.php'>Đăng xuất</a>";
