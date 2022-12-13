<?php


require_once('../vendor/autoload.php');


use Amenadiel\JpGraph\Graph\Graph;
use Amenadiel\JpGraph\Plot\LinePlot;

// Создадим немного данных для визуализации:
$ydata = array();
$objXmlDocument = simplexml_load_file("test.xml");

if ($objXmlDocument === FALSE) {
    echo "There were errors parsing the XML file.\n";
    foreach (libxml_get_errors() as $error) {
        echo $error->message;
    }
    exit;
}
$xdata = array();
$ydata = array();
$objJsonDocument = json_encode($objXmlDocument);

$arrOutput = json_decode($objJsonDocument, TRUE);
$tests = $arrOutput["testsuite"]["testcase"];
foreach ($tests as $test) {
    array_push($ydata, $test["@attributes"]["assertions"]);
    array_push($xdata, $test["@attributes"]["time"]);
}


$graph = new Graph(800, 600, 'auto', 10, true);

// Указываем, какие оси использовать:
$graph->SetScale('textlin');

/*
Создаем экземпляр класса линейного графика, передадим
ему нужные значения:
*/
$lineplot = new LinePlot($ydata);

// Задаём цвет кривой
$lineplot->SetColor('red');

// Присоединяем кривую к графику:
$graph->Add($lineplot);

// Даем графику имя:
$graph->title->Set('Tests with assertions');

/*
Если планируете использовать кириллицу, то необходимо
использовать TTF-шрифты, которые её поддерживают,
например arial.
*/
$graph->title->SetFont(FF_ARIAL, FS_NORMAL);
$graph->xaxis->title->SetFont(FF_VERDANA, FS_ITALIC);
$graph->yaxis->title->SetFont(FF_TIMES, FS_BOLD);

// Назовем оси:
$graph->xaxis->title->Set('Test number');
$graph->yaxis->title->Set('Assertions');

// Выделим оси цветом:
$graph->xaxis->SetColor('#СС0000');
$graph->yaxis->SetColor('#СС0000');

// Зададим толщину кривой:
$lineplot->SetWeight(3);

// Обозначим точки звездочками, задав тип маркера:
$lineplot->mark->SetType(MARK_FILLEDCIRCLE);

// Выведем значения над каждой из точек:
$lineplot->value->Show();

// Фон графика зальем градиентом:
$graph->SetBackgroundGradient('red', 'orange');

// Придадим графику тень:

$gdImgHandler = $graph->Stroke(_IMG_HANDLER);
$stamp = imagecreatefrompng('4k.png');
// Set the margins for the stamp and get the height/width of the stamp image

$sx = imagesx($stamp);
$sy = imagesy($stamp);


imagecopy($gdImgHandler, $stamp, imagesx($gdImgHandler) - $sx , imagesy($gdImgHandler) - $sy , 0, 0, imagesx($stamp), imagesy($stamp));
//выводим рисунок
Header("Content-Type: image/png");
ImagePNG($gdImgHandler);

