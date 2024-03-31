<?php
declare( strict_types = 1 );
namespace Battle\Model;
class Play {
    private $player1;
    private $player2;

    public function __construct($player1, $player2) {
        $this->player1 = $player1;
        $this->player2 = $player2;
    }

    public function getPlayer1() {
        return $this->player1;
    }

    public function getPlayer2() {
        return $this->player2;
    }

    public function attack($attacker, $target) {
        //Logic to calculate damage and update target health
    }

    public function endTurn() {
        // Logic to end the turn and move on to the next player
    }
}
?>
