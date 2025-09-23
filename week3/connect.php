<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb_sql"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}


$username = $_POST['username'];
$password = $_POST['password'];
$email    = $_POST['email'];
$phone    = $_POST['phone'];


$sql = "INSERT INTO mydb_sql (ho_ten, password, email, phone) 
        VALUES ('$username', '$password', '$email', '$phone')";

if ($conn->query($sql) === TRUE) {

    header("Location: list_admin_add.php");
    exit();
} else {
    echo "Lỗi: " . $conn->error;
}

$conn->close();
?>
