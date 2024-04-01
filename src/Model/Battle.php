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

    public function getTanks(){
        return $this->tanks;
    }

    public function getPlayers(){
        return $this->players;
    }
    
    public function getWinnerTank(){
        return reset($this->tanks);
    }

    public function addTank(Tank $tank, String $playerID){
        $this->tanks[] = $tank;
        $this->players[$tank->getId()] = $playerID;
    }

    private function beforeInit(){
        $tank1 = $this->tanks[0];
        $tank2 = $this->tanks[1];
        $tank1->move(0, 0); // Move tank 1 to the top-left corner
        $tank2->move($this->map->getWidth() - 1, $this->map->getHeight() - 1); // Move tank 2 to the bottom-right corner
        $tank1->setScore(0); // Reset the score of tank 1
        $tank2->setScore(0); // Reset the score of tank 2
    }   

    public function simulate(){
        $turn = 1;
        $remainingTanks = count($this->tanks);
        $tank1 = $this->tanks[0];
        $tank2 = $this->tanks[1];
        $this->beforeInit();
        $battleTanks = $this->tanks;

        while ($remainingTanks > 1) {
            //echo "Turn $turn\n";

            // Get AI moves for each tank
            $moves = [];
            $moves[$tank1->getId()] = AI::getNextMove($tank1, $this->map, $tank2->getPosition()['x'], $tank2->getPosition()['y']);
            $moves[$tank2->getId()] = AI::getNextMove($tank2, $this->map, $tank1->getPosition()['x'], $tank1->getPosition()['y']);

            // Move the tanks
            foreach ($battleTanks as $tank) {
                $move = $moves[$tank->getId()][1];
                if ($this->map->isValidPosition($move['x'], $move['y'])) {
                    $tank->move($move['x'], $move['y']);
                }   
            }

            // Perform attacks
            foreach ($battleTanks as $attackingTank) {
                $tanksInRange = $this->getTanksInRange($attackingTank);
                foreach ($tanksInRange as $defendingTank) {
                    $attackingTank->attack($defendingTank);
                    //echo "Tank {$attackingTank->getId()} attack to Tank {$defendingTank->getId()} (healt: {$defendingTank->gethealth()})\n";
                }
            }

            // Eliminate destroyed tanks
            $battleTanks = array_filter($battleTanks, function ($tank) {
                return $tank->gethealth() > 0;
            });
            $remainingTanks = count($battleTanks);

            $turn++;
        }

        // Determine the winner
        $winner = reset($battleTanks);
        $winnerId = $winner->getId();
        $winnerPlayer = $this->players[$winnerId];
        $this->tanks = $battleTanks;
        return $winnerPlayer;

    }

    public function getTanksInRange(Tank $tank){
        $tanksInRange = [];
        $position = $tank->getPosition();
        foreach ($this->tanks as $otherTank) {
            if ($otherTank !== $tank) {
                $otherPosition = $otherTank->getPosition();
                $distance = sqrt(pow($position['x'] - $otherPosition['x'], 2) + pow($position['y'] - $otherPosition['y'], 2));
                if ($distance <= $tank->getturretRange()) {
                    $tanksInRange[] = $otherTank;
                }
            }
        }
        return $tanksInRange;
    }
}