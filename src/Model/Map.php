<?php
declare( strict_types = 1 );
namespace Battle\Model;

class Map
{
    private $id;
    private $name;
    public $width;
    public $height;
    public $obstacles;
    public function __construct($id, $name, $width, $height, array $obstacles)
    {
        $this->id = $id;
        $this->name = $name;
        $this->width = $width;
        $this->height = $height;
        $this->obstacles = $obstacles;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function addObstacle(Obstacle $obstacle)
    {
        $this->obstacles[] = $obstacle;
    }

    public function removeObstacle(Obstacle $obstacle)
    {
        $key = array_search($obstacle, $this->obstacles);
        if ($key !== false) {
            unset($this->obstacles[$key]);
        }
    }

    public function isPositionOccupied($x, $y)
    {
        foreach ($this->obstacles as $obstacle) {
            if ($obstacle->x == $x && $obstacle->y == $y) {
                return true;
            }
        }
        return false;
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