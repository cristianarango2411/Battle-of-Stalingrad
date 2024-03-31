<?php
declare( strict_types = 1 );
namespace Battle\Model;

class Player {
    private $userName;
    private $id;
    private $score;

    public function __construct($userName, $id) {
        $this->userName = $userName;
        $this->id = $id;
    }

    public static function fromArray(array $data) {
        return new self($data['userName'], $data['id']);
    }

    public function getName() {
        return $this->userName;
    }

    public function getScore() {
        return $this->score;
    }

    public function setScore($score) {
        $this->score = $score;
    }
    public function getId() {
        return $this->id;
    }

    public function setI($id) {
        $this->id = $id;
    }
}
?>

