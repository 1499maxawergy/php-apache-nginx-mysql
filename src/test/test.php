<?php


use PHPUnit\Framework\TestCase;

class StackTest extends TestCase
{
    protected $list = array();

    protected function MyPow($number, $level){
        $result = 1;
        for ($i = 1; $i <= $level; $i++){
            $result *= $number;
        }
        return $result;
    }

    protected function MyABS($number){
        if ($number < 0){
            $number *= -1;
        }
        return $number;
    }

    protected function prepare()
    {
        $count = 0;
        for ($i = -10; $i < 1; $i++){
            for($j = 10; $j < 20; $j++)
                array_push($this->list, $i, $j, $this->MyABS($i), $this->MyABS($j), $this->MyPow($i, $j));
        }
    }

    public function test(){
        $this->prepare();
        for ($i = 2; $i < count($this->list); $i+=5){
            $this->assertSame($this->list[$i], abs($this->list[$i - 2]));
            $this->assertSame($this->list[$i + 1], abs($this->list[$i - 1]));
            $this->assertSame($this->list[$i + 2], pow($this->list[$i - 2], $this->list[$i - 1]));
        }
    }
}

?>
