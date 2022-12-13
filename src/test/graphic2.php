<?php
// Подключаем файл с классами для работы со столбиками
use Amenadiel\JpGraph\Plot\AccBarPlot;
use Amenadiel\JpGraph\Plot\BarPlot;

require_once('../vendor/autoload.php');
use Amenadiel\JpGraph\Graph\Graph;


$objXmlDocument = simplexml_load_file("test.xml");

if ($objXmlDocument === FALSE) {
    echo "There were errors parsing the XML file.\n";
    foreach (libxml_get_errors() as $error) {
        echo $error->message;
    }
    exit;
}
$ydata = array();
$objJsonDocument = json_encode($objXmlDocument);

$arrOutput = json_decode($objJsonDocument, TRUE);
$tests = $arrOutput["testsuite"]["testcase"];
foreach ($tests as $test) {
    array_push($ydata, $test["@attributes"]["assertions"]);
}




$graph = new Graph(800, 600, 'auto', 10, true);

// Указываем, какие оси использовать:
$graph->SetScale('textlin');

// Фон графика зальем градиентом:
$graph->SetBackgroundGradient('green', 'yellow');


// Создаём один столбик
$bplot = new BarPlot($ydata);
$bplot->SetLegend('Assertions');

$graph->title->Set('Assertions on test count');
$graph->title->SetFont(FF_ARIAL, FS_BOLD,16);

// Объединяем столбики
$accbplot = new AccBarPlot(array($bplot));
$accbplot->SetColor('darkgray');
$accbplot->SetWeight(3);

// Присоединяем столбики к графику:
$graph->Add($accbplot);

$gdImgHandler = $graph->Stroke(_IMG_HANDLER);
$stamp = imagecreatefrompng('4k.png');
// Set the margins for the stamp and get the height/width of the stamp image

$sx = imagesx($stamp);
$sy = imagesy($stamp);


imagecopy($gdImgHandler, $stamp, imagesx($gdImgHandler) - $sx , imagesy($gdImgHandler) - $sy , 0, 0, imagesx($stamp), imagesy($stamp));
//выводим рисунок
Header("Content-Type: image/png");
ImagePNG($gdImgHandler);