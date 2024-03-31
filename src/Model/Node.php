<?php
declare( strict_types = 1 );
namespace Battle\Model;

class Node
{
    public $x;
    public $y;
    public $parent;
    public $g; // Cost from starting node to this node
    public $h; //Estimated heuristic from this node to the target
    public $f; // Evaluation function (g + h)

    public function __construct($x, $y, $parent = null)
    {
        $this->x = $x;
        $this->y = $y;
        $this->parent = $parent;
        if ($parent) {
            $dx = abs($x - $parent->x);
            $dy = abs($y - $parent->y);
            if ($dx == 1 && $dy == 1) {
                $this->g = $parent->g + sqrt(2); // Diagonal cost (1.41421356)
            } else {
                $this->g = $parent->g + 1; // Horizontal/vertical cost (1)
            }
        } else {
            $this->g = 0;
        }
    }
    
    public function setHeuristic($targetX, $targetY)
    {
        // We use the Euclidean distance as a heuristic
        $dx = $targetX - $this->x;
        $dy = $targetY - $this->y;
        $this->h = sqrt($dx * $dx + $dy * $dy);
        $this->f = $this->g + $this->h;
    }
}