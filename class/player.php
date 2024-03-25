<?php


class Player {
    public $userName;
    public $id;
    //id is randomly generated
    public $score;
    //start and end date

    public function __construct($userName, $id) {
        $this->userName = $userName;
        $this->id = $id;
        $this->score = $score;
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

