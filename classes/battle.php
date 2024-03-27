<?php

class Battle
{
    public $map;
    public $tanks = [];
    public $players = [];
    public $ai;

    public function __construct(Map $map, AI $ai){
        $this->map = $map;
        $this->ai = $ai;
    }

    public function addTank(Tank $tank, Player $player){
        $this->tanks[] = $tank;
        $this->players[$tank->id] = $player;
    }


    public function simulate(){
        $turn = 1;
        $remainingTanks = count($this->tanks);

        while ($remainingTanks > 1) {
            echo "Turno $turn\n";

            // Get AI moves for each tank
            $moves = [];
            foreach ($this->tanks as $tank) {
                $moves[$tank->id] = $this->ai->getNextMove($tank, $this->map);
            }

            // Move the tanks
            foreach ($this->tanks as $tank) {
                $move = $moves[$tank->id];
                if ($this->map->isValidPosition($move['x'], $move['y'])) {
                    $tank->move($move['x'], $move['y']);
                }   
            }

            // Perform attacks
            foreach ($this->tanks as $attackingTank) {
                $tanksInRange = $this->getTanksInRange($attackingTank);
                foreach ($tanksInRange as $defendingTank) {
                    $attackingTank->attack($defendingTank);
                    echo "Tanque {$attackingTank->id} ataca a Tanque {$defendingTank->id} (Salud restante: {$defendingTank->health})\n";
                }
            }

            // Eliminate destroyed tanks
            $this->tanks = array_filter($this->tanks, function ($tank) {
            return $tank->health > 0;
            });
            $remainingTanks = count($this->tanks);

            $turn++;
        }

        // Determine the winner
        $winner = reset($this->tanks);
        $winnerId = $winner->id;
        $winnerPlayer = $this->players[$winnerId];

        echo "El ganador es: {$winnerPlayer->id}\n";

        // Save winner's score
        $scoreManager = new ScoreManager($db);
        $scoreManager->saveScore($winnerPlayer->id, $turn);
    }

    public function getTanksInRange(Tank $tank){
        $tanksInRange = [];
        $position = $tank->getPosition();
        foreach ($this->tanks as $otherTank) {
            if ($otherTank !== $tank) {
                $otherPosition = $otherTank->getPosition();
                $distance = sqrt(pow($position['x'] - $otherPosition['x'], 2) + pow($position['y'] - $otherPosition['y'], 2));
                if ($distance <= $tank->turretRange) {
                    $tanksInRange[] = $otherTank;
                }
            }
        }
        return $tanksInRange;
    }

    private function isInRange(Tank $tank1, Tank $tank2)
    {
        $position1 = $tank1->getPosition();
        $position2 = $tank2->getPosition();
        $distance = sqrt(pow($position1['x'] - $position2['x'], 2) + pow($position1['y'] - $position2['y'], 2));
        return $distance <= $tank1->turretRange;
    }
}



?>