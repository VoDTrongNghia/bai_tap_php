<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Form Thông Tin Sinh Viên</title>
</head>
<body>
  <h2>Form Thông Tin Sinh Viên</h2>
  <form action="info.php" method="post" enctype="multipart/form-data">
    
    <!-- text -->
    <label>Họ và tên:</label><br>
    <input type="text" name="hoten" required><br><br>

    <!-- MSSV -->
    <label>MSSV:</label><br>
    <input type="text" name="mssv" required><br><br>

    <!-- radio -->
    <label>Giới tính:</label><br>
    <input type="radio" name="gioitinh" value="Nam" required> Nam
    <input type="radio" name="gioitinh" value="Nữ"> Nữ
    <input type="radio" name="gioitinh" value="Khác"> Khác
    <br><br>

    <!-- checkbox -->
    <label>Ngôn ngữ:</label><br>
    <input type="checkbox" name="language[]" value="C++"> C++
    <input type="checkbox" name="language[]" value="PHP"> PHP
    <input type="checkbox" name="language[]" value="Java"> Java
    <br><br>

    <!-- select (dropdown) -->
    <label>Thành phố:</label>
    <select name="thanhpho" required>
      <option value="">-- Chọn thành phố --</option>
      <option value="Hà Nội">Hà Nội</option>
      <option value="Hồ Chí Minh">Hồ Chí Minh</option>
      <option value="Đà Nẵng">Đà Nẵng</option>
      <option value="Cần Thơ">Cần Thơ</option>
    </select>
    <br><br>

    <!-- Tin nhắn -->
     <label>Tin nhắn:</label><br>
     <textarea rows="4" cols="20">

    </textarea>
    <!-- hidden -->
    <input type="hidden" name="ma_bimat" value="123456">
<br>
    <input type="submit" value="Gửi">
  </form>
</body>
</html>
