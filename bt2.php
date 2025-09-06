<?php
$thanh_pho = array("Tokyo", "Mexico City", "New York City", "Mumbai", "Seoul", "Shanghai",
"Lagos", "Buenos Aires", "Cairo", "London");

for ($i = 0; $i < count($thanh_pho); $i++) {
    echo $thanh_pho[$i];
    if ($i < count($thanh_pho) - 1) {
        echo ", ";
    }
}

sort($thanh_pho);
echo "<h3>Danh sách thành phố (sắp xếp, in bằng &lt;ul&gt;):</h3>";
echo "<ul>";
foreach ($thanh_pho as $tp) {
    echo "<li>$tp</li>";
}
echo "</ul>";


array_push($thanh_pho, "Los Angeles", "Calcutta", "Osaka", "Beijing");

// Sắp xếp lại
sort($thanh_pho);

echo "<h3>Danh sách thành phố (sau khi thêm và sắp xếp lại):</h3>";
echo "<ul>";
foreach ($thanh_pho as $tp) {
    echo "<li>$tp</li>";
}
echo "</ul>";
?>
