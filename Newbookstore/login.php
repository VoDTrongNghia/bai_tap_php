<?php
require_once "config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['ten_dang_nhap'];
    $password = $_POST['mat_khau'];

    $sql = "SELECT * FROM nguoi_dung WHERE ten_dang_nhap = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['mat_khau'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['ten_dang_nhap'];
            $_SESSION['role'] = $user['vai_tro'];

            if ($user['vai_tro'] === 'admin') {
                header("Location: admin/index.php");
                exit;
            }
        }
    }

    $error = "Sai tài khoản hoặc mật khẩu!";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
</head>
<body>
    <h1>Đăng nhập</h1>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <form method="POST">
        <label for="ten_dang_nhap">Tên đăng nhập hoặc Email:</label>
        <input type="text" name="ten_dang_nhap" id="ten_dang_nhap" required>
        <br>
        <label for="mat_khau">Mật khẩu:</label>
        <input type="password" name="mat_khau" id="mat_khau" required>
        <br>
        <button type="submit">Đăng nhập</button>
    </form>
</body>
</html>
