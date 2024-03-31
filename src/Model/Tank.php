<?php
declare( strict_types = 1 );
namespace Battle\Model;

class Tank {
    private $id;
    private $name;
    private $health;
    private $attack;
    private $defense;
    private $speed; 
    private $fuelRange;
    private $position;
    private $turretRange;


    public function __construct($id, $name, $health, $attack, $defense, $speed, $fuelRange, $turretRange) {
        $this->attack = $attack;
        $this->defense = $defense;
        $this->id = $id;
        $this->name = $name;
        $this->health = $health;
        $this->speed = $speed;
        $this->fuelRange = $fuelRange;
        $this->turretRange = $turretRange;
    }
    public static function fromArray(array $data) {
        return new self($data['id'], $data['name'], $data['health'], $data['attack'], $data['defense'], $data['speed'], $data['fuelRange'], $data['turretRange']);
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

    public function getId() { return $this->id; }

    public function setId($id) { $this->id = $id; }

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

    public function getPosition()
    {
        return $this->position;
    }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'health' => $this->health,
            'speed' => $this->speed,
            'fuelRange' => $this->fuelRange,
            'turretRange' => $this->turretRange,
            'attack' => $this->attack,
            'defense' => $this->defense,
        ];
    }


}