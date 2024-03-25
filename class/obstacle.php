<?php

class Obstacle
{
    public $type;
    public $x;
    public $y;

    public function __construct($type, $x, $y)
    {
        $this->type = $type;
        $this->x = $x;
        $this->y = $y;
    }
}


?>
