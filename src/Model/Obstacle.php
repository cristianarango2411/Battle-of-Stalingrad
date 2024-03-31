<?php
declare( strict_types = 1 );
namespace Battle\Model;
class Obstacle
{
    public $x;
    public $y;

    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }
}


?>
