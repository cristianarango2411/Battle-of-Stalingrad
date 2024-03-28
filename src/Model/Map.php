<?php

class Map
{
    public $width;
    public $height;
    public $obstacles = [];

    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function addObstacle(Obstacle $obstacle)
    {
        $this->obstacles[] = $obstacle;
    }

    public function isValidPosition($x, $y)
    {
        // Check if the position is within the map limits
        if ($x < 0 || $x >= $this->width || $y < 0 || $y >= $this->height) {
            return false;
        }

        // Check if there is an obstacle in that position
        foreach ($this->obstacles as $obstacle) {
            if ($obstacle->x == $x && $obstacle->y == $y) {
                return false;
            }
        }

        return true;
    }
}



?>