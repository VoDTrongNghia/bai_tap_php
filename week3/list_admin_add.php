<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb_sql";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}


$sql = "SELECT id, ho_ten, email, phone FROM mydb_sql";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách tài khoản</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 80%;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background: #007bff;
            color: #fff;
            padding: 10px;
        }
        td {
            text-align: center;
            padding: 8px;
        }
        a {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 5px;
            margin: 0 5px;
            color: #fff;
        }
        .btn-edit {
            background: #28a745;
        }
        .btn-delete {
            background: #dc3545;
        }
        .btn-add {
            display: inline-block;
            background: #007bff;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Danh sách tài khoản quản trị</h2>
        <a class="btn-add" href="add_admin.php">+ Thêm mới</a>
        <table>
            <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Hành động</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".$row["id"]."</td>
                            <td>".$row["ho_ten"]."</td>
                            <td>".$row["email"]."</td>
                            <td>".$row["phone"]."</td>
                            <td>
                                <a class='btn-edit' href='edit_admin.php?id=".$row["id"]."'>Sửa</a>
                                <a class='btn-delete' href='delete_admin.php?id=".$row["id"]."' onclick=\"return confirm('Bạn có chắc chắn muốn xóa?');\">Xóa</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Chưa có tài khoản nào</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
