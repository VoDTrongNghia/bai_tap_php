<?php
$dictionary = [
    "apple" => "quả táo",
    "banana" => "quả chuối",
    "car" => "xe hơi",
    "dog" => "con chó",
    "cat" => "con mèo"
];

$dictionary["push"]= "đẩy lên, đấm, ấn";
$dictionary["talk"]= "nói";

// sửa 
$dictionary["car"] = "oto kh phai xe hoi";
$dictionary["apple"] = "qua bom";

//xóa 
unset($dictionary["dog"]);

// In kết quả
echo "<pre>";
print_r($dictionary);
echo "</pre>";
?>
