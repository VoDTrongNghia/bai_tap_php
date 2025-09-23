<?php
echo "<table border='1' cellspacing='0' cellpadding='40'>";

for ($row = 1; $row <= 8; $row++) {
    echo "<tr>";
    for ($col = 1; $col <= 8; $col++) {
        if (($row + $col) % 2 == 0) {
            echo "<td style='background-color: white; width: 60px; height: 60px;'></td>";
        } else {
            echo "<td style='background-color: black; width: 60px; height: 60px;'></td>";
        }
    }
    echo "</tr>";
}

echo "</table>";
?>
