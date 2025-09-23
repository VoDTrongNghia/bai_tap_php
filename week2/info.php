<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoten = $_POST['hoten'] ?? '';
    $mssv = $_POST['mssv'] ?? '';
    $gioitinh = $_POST['gioitinh'] ?? '';
    $ngonngu = $_POST['language'] ?? []; 
    $thanhpho = $_POST['thanhpho'] ?? '';
    $tinnhan = $_POST['tinnhan'] ?? '';
    $ma_bimat = $_POST['ma_bimat'] ?? '';
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title> Thông Tin Đã Nhận Được</title>
</head>
<body>
  <h2>Thông Tin Đã Nhận Được</h2>
  <p><b>Họ và tên:</b> <?= htmlspecialchars($hoten) ?></p>
  <p><b>MSSV:</b> <?= htmlspecialchars($mssv) ?></p>
  <p><b>Giới tính:</b> <?= htmlspecialchars($gioitinh) ?></p>

  <p><b>Ngôn ngữ:</b> 
    <?php 
      if (!empty($ngonngu)) {
          echo implode(", ", array_map("htmlspecialchars", $ngonngu));
      } else {
          echo "Chưa chọn";
      }
    ?>
  </p>

  <p><b>Thành phố:</b> <?= htmlspecialchars($thanhpho) ?></p>
  <p><b>Tin nhắn:</b> <?= nl2br(htmlspecialchars($tinnhan)) ?></p>

  <p><i>Mã bí mật (hidden):</i> <?= htmlspecialchars($ma_bimat) ?></p>
</body>
</html>
