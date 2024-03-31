<?php
declare( strict_types = 1 );
namespace Battle\Model;

use Battle\Model\Tank;
use Battle\Model\AI;

class Battle
{
    private $map;
    private $tanks = [];
    private $players = [];

    public function __construct(Map $map){
        $this->map = $map;
    }

    public function addTank(Tank $tank, String $playerID){
        $this->tanks[] = $tank;
        $this->players[$tank->getId()] = $playerID;
    }


    public function simulate(){
        $turn = 1;
        $remainingTanks = count($this->tanks);
        $tank1 = $this->tanks[0];
        $tank2 = $this->tanks[1];

        while ($remainingTanks > 1) {
            echo "Turno $turn\n";

            // Get AI moves for each tank
            $moves = [];
            /*foreach ($this->tanks as $tank) {
                $moves[$tank->id] = AI::getNextMove($tank, $this->map);
            }*/
            $moves[$tank1->id] = AI::getNextMove($tank1, $this->map, $tank2->getPosition()['x'], $tank2->getPosition()['y']);
            $moves[$tank2->id] = AI::getNextMove($tank2, $this->map, $tank1->getPosition()['x'], $tank1->getPosition()['y']);

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
        //$scoreManager = new ScoreManager($db);
        //$scoreManager->saveScore($winnerPlayer->id, $turn);
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