<?php

for ($i = 30; $i<=100; $i++) {
    $points = 5 * pow(1.03, $i);
    $points = round($points);
    echo "<div style='width: 5px; height: {$points}px; background: green; display: inline-block;'></div>";
}
echo "<hr>";
for ($i = 30; $i<=100; $i+=2) {
    echo $i . '% - ' . round((pow(1.03, $i))) . ' pts<br>';
}