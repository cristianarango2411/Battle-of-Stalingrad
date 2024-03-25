<?php

class Tank {
    public $name;
    public $health;
    public $attack;
    public $defense;
    public $speed; 
    public $fuelRange;
    public $position;


    public function __construct($name, $health, $attack, $defense, $speed, $fuelRange, $x, $y) {
        $this->attack = $attack;
        $this->defense = $defense;
        $this->name = $name;
        $this->health = $health;
        $this ->speed = $speed;
        $this ->fuelRange = $fuelRange;
        $this->position = ['x' => $x, 'y' => $y];
    }

    public function attack(Tank $target)
    {
        $damage = max(0, $this->attack - $target->defense);
        $target->health -= $damage;
    }

    public function move($x, $y)
    {
        $this->position['x'] = $x;
        $this->position['y'] = $y;
    }


    public function getName() {
        return $this->name;
    }
    public function setName($name) {
        $this->name = $name;
    }

    public function gethealth() {
        return $this->health;
    }

    public function sethealth($health) {
        $this->health = $health;
    }

    public function getSpeed() {
        return $this->speed;
    }

    public function setSpeed($speed) {
        $this->speed = $speed;
    }

    public function getfuelRange() {
        return $this->fuelRange;
    }

    public function setfuelRange($fuelRange) {
        $this->fuelRange = $fuelRange;
    }

    public function getPosition()
    {
        return $this->position;
    }


}
?>