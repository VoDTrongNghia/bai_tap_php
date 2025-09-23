<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb_sql";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$id    = $_POST['id'];
$ho_ten = $_POST['ho_ten'];
$email = $_POST['email'];
$phone = $_POST['phone'];

$sql = "UPDATE mydb_sql SET ho_ten='$ho_ten', email='$email', phone='$phone' WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: list_admin_add.php");
    exit();
} else {
    echo "Lỗi: " . $conn->error;
}

$conn->close();
?>
