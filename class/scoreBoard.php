<?php

class Scoreboard {
    private $scores;

    public function __construct($scores) {
        $this->scores = $scores;
    }

    public function guardarScore($score_id, $score) {
        // Lógica para guardar la puntuación en la tabla de clasificación
    }

    public function getScoreboard($tipe) {
        // Lógica para obtener la tabla de clasificación (diaria, mensual, global)
    }
}




?>

