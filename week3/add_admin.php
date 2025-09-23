<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm mới quản trị viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
            width: 350px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        label {
            float: left;
            margin: 8px 0 5px 0;
            font-weight: bold;
            color: #444;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
        }
        input:focus {
            border-color: #007bff;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thêm mới quản trị viên</h2>
        <form action="connect.php" method="POST">
            <label>Tên đăng nhập:</label>
            <input type="text" name="username" required>

            <label>Mật khẩu:</label>
            <input type="password" name="password" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Số điện thoại:</label>
            <input type="text" name="phone" required>

            <button type="submit">Thêm mới</button>
        </form>
    </div>
</body>
</html>
