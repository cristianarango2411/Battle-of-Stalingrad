<?php

namespace Battle;

class Tank {
    public $id;
    public $name;
    public $health;
    public $attack;
    public $defense;
    public $speed; 
    public $fuelRange;
    public $position;
    public $turretRange;
    public $viewport;
    public $barrel;
    public $turretRing;
    public $commanderHatch;
    public $wheels;



    public function __construct($id, $name, $health, $attack, $defense, $speed, $fuelRange, $turretRange, $viewport, $barrel, $turretRing, $commanderHatch, $wheels, $x, $y) {
        $this->attack = $attack;
        $this->defense = $defense;
        $this->id = $id;
        $this->name = $name;
        $this->health = $health;
        $this ->speed = $speed;
        $this ->fuelRange = $fuelRange;
        $this ->turretRange = $turretRange;
        $this ->viewport = $viewport;
        $this ->barrel = $barrel;
        $this ->turretRing = $turretRing;
        $this ->commanderHatch = $commanderHatch;
        $this ->wheels = $wheels;
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

    public function getturretRange() {
        return $this->turretRange;
    }

    public function setturretRange($turretRange) {
        $this->turretRange = $turretRange;
    }

    public function getviewport() {
        return $this->viewport;
    }

    public function setviewport($viewport) {
        $this->viewport = $viewport;
    }

    public function getbarrel() {
        return $this->barrel;
    }

    public function setbarrel($barrel) {
        $this->barrel = $barrel;
    }

    public function getturretRing() {
        return $this->turretRing;
    }

    public function setturretRing($turretRing) {
        $this->turretRing = $turretRing;
    }

    public function getcommanderHatch() {
        return $this->commanderHatch;
    }

    public function setcommanderHatch($commanderHatch) {
        $this->commanderHatch = $commanderHatch;
    }

    public function getwheels() {
        return $this->wheels;
    }

    public function setwheels($wheels) {
        $this->wheels = $wheels;
    }

    public function getPosition()
    {
        return $this->position;
    }


}
?>