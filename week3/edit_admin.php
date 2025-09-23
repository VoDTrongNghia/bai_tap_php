<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb_sql";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$id = $_GET['id'];
$sql = "SELECT * FROM mydb_sql WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa quản trị viên</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
        }
        .form-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 8px;
            margin: 6px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Sửa quản trị viên</h2>
        <form action="update_admin.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <label>Tên đăng nhập:</label><br>
            <input type="text" name="ho_ten" value="<?php echo $row['ho_ten']; ?>" required><br>

            <label>Email:</label><br>
            <input type="email" name="email" value="<?php echo $row['email']; ?>" required><br>

            <label>Số điện thoại:</label><br>
            <input type="text" name="phone" value="<?php echo $row['phone']; ?>" required><br>

            <button type="submit">Cập nhật</button>
        </form>
    </div>
</body>
</
