<?php


exec("../vendor/phpunit/phpunit/phpunit --log-junit test.xml test.php");
echo '<h2 align="center">Линейная диаграмма </h2>';
echo '<center><img src="graphic1.php"></center>';
echo "<hr>";
echo '<h2 align="center">Гистограмма</h2>';
echo '<center><img src="graphic2.php"></center>';
echo "<hr>";
echo '<h2 align="center"> Круговая диаграмма</h2>';
echo '<center><img src="graphic3.php"></center>';
echo "<hr>";
?>
