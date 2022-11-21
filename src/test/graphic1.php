<?php
function draw_axises($im_width,$im_heignt)
{
    global $im,$black,$l_grey,$x0,$y0,$maxX,$maxY;
    $x0=25.0; //начало оси координат по X
    $y0=20.0; //начало оси координат по Y
    $maxX=$im_width-$x0; //максимальное значение оси
//координат по X в пикселах
    $maxY=$im_heignt-$y0; //максимальное значение оси
//координат по Y в пикселах
//рисуем ось X
    imageline($im, $x0, $maxY, $maxX, $maxY, $black);
//рисуем ось Y
    imageline($im, $x0, $y0, $x0, $maxY, $black);
//рисуем стрелку на оси X
    $xArrow[0]=$maxX-6; $xArrow[1]=$maxY-2;
    $xArrow[2]=$maxX; $xArrow[3]=$maxY;
    $xArrow[4]=$maxX-6; $xArrow[5]=$maxY+2;
    imagefilledpolygon($im, $xArrow, 3, $black);
//рисуем стрелку на оси Y
    $yArrow[0]=$x0-2; $yArrow[1]=$y0+6;
    $yArrow[2]=$x0; $yArrow[3]=$y0;
    $yArrow[4]=$x0+2; $yArrow[5]=$y0+6;
    imagefilledpolygon($im, $yArrow, 3, $black);
}

function draw_grid($xStep,$yStep,$xCoef,$yCoef)
{global $im,$black,$l_grey,$x0,$y0,$maxX,$maxY;
    $xSteps=($maxX-$x0)/$xStep-1; //определяем количество
//шагов по оси X
    $ySteps=($maxY-$y0)/$yStep-1; //определяем количество
//шагов по оси Y
//выводим сетку по оси X
    for($i=1;$i<$xSteps+1;$i++){
        imageline($im, $x0+$xStep*$i, $y0, $x0+$xStep*$i,$maxY-1, $l_grey);
        //при необходимости выводим значения линий сетки по оси X
        imagestring($im, 1, ($x0+$xStep*$i)-1, $maxY+2, $i*$xCoef, $black);
    }
//выводим сетку по оси Y
    for($i=1;$i<$ySteps+1;$i++){
        imageline($im, $x0+1, $maxY-$yStep*$i, $maxX,
            $maxY-$yStep*$i, $l_grey);
        //при необходимости выводим значения линий сетки по оси Y
        imagestring($im, 1, 0, ($maxY-$yStep*$i)-3, $i*$yCoef, $black);
    }
}

function draw_data($data_x,$data_y,$points_count,$color)
{global $im,$x0,$y0,$maxY,$scaleX,$scaleY;
    for($i=1;$i<$points_count;$i++){
        //рисуем линейный график по точкам из массивов данных
        imageline($im, $x0+$data_x[$i-1]*$scaleX, $maxY-$data_y[$i-1]*$scaleY,
            $x0+$data_x[$i]*$scaleX, $maxY-$data_y[$i]*$scaleY,$color);
    }
}

//$xx = array();
//$yy = array();
//for ($i = -10; $i < 1; $i++){
//    for($j = 10; $j < 20; $j++)
//        array_push($yy, $i, $j);
//        array_push($xx, abs($i), abs($j), pow($i, $j));
//}

//создаем рисунок шириной 500 и высотой 400 пикселов
$im = @ImageCreate(500, 400);
$white = ImageColorAllocate ($im, 255, 255, 255);
$black = ImageColorAllocate ($im, 0, 0, 0);
$red = ImageColorAllocate ($im, 255, 0, 0);
$green = ImageColorAllocate ($im, 0, 255, 0);
$blue = ImageColorAllocate ($im, 0, 0, 255);
$yellow = ImageColorAllocate ($im, 255, 255, 0);
$magenta = ImageColorAllocate ($im, 255, 0, 255);
$cyan = ImageColorAllocate ($im, 0, 255, 255);
$l_grey = ImageColorAllocate ($im, 221, 221, 221);
//рисуем оси координат
draw_axises(500,400);
//задаем массивы данных графиков
$x1[0] = 0; $x1[1] = 2; $x1[2] = 2.1; $x1[3] = 2.2; $x1[4] = 16; $x1[5] = 30;
$y1[0] = 1; $y1[1] = 1; $y1[2] = 0; $y1[3] = 1; $y1[4] = 1; $y1[5] = 1;


//вычисляем масштаб преобразования данных
//в координаты рабочей области
$scaleX=15;
$scaleY=70;
//задаем шаг для координатной сетки в пикселах
$xStep=30;
$yStep=30;
//рисуем координатную сетку
draw_grid($xStep,$yStep,
    round($xStep/$scaleX,1),
    round($yStep/$scaleY,1),
    true);
//рисуем первый график
draw_data($x1,$y1,6,$red);
$stamp = imagecreatefrompng('stamp.png');
// Set the margins for the stamp and get the height/width of the stamp image
$marge_right = 100;
$marge_bottom = 100;
$sx = imagesx($stamp);
$sy = imagesy($stamp);

// Copy the stamp image onto our photo using the margin offsets and the photo
// width to calculate positioning of the stamp.
imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
//выводим рисунок
Header("Content-Type: image/png");
ImagePNG($im);
//освобождаем занимаемую рисунком память
imagedestroy($im);