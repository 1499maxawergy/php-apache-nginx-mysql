<?php

exec("phpunit --log-junit 1.xml test.php");
echo '<center><img src="graphic1.php"></center>';
echo '<h2 align="center">График успешности тестов (1 - успех, 0 - провал)</h2>';

echo '<center><img src="graphic2.php"></center>';
echo '<h2 align="center">График времени выполнения тестов (в мс)</h2>';

echo '<center><img src="graphic3.php"></center>';
echo '<h2 align="center">Количество Assertов в тестах</h2>';
?>
