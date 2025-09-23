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

$sql = "DELETE FROM mydb_sql WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: list_admin_add.php");
    exit();
} else {
    echo "Lỗi: " . $conn->error;
}

$conn->close();
?>
