<?php
namespace Battle;

Use Battle\Model\RedisConnection;
use Battle\Tank;

require_once 'v_tank.php';


$tank = new Tank($id, $name, $health, $attack, $defense, $speed, $fuelRange, $turretRange, $viewport, $barrel, $turretRing, $commanderHatch, $wheels, $x, $y);

class ScoreManager
 {
    private $scores;
    public $redis;

    public function __construct($scores)
    {
        $this->scores = $scores;
        
    }

    //controller to load tank from database
    public function loadTank($tank_id) {

        $table_tank = $_POST['table_tank_get'];//nombramos la tabla

        $redisConnection = new RedisConnection();

        // Obtenemos la tabla
        $tableTank=$redisConnection->getTable($table_tank);

        var_dump($tableTank);
        

    }




    public function loadMap($map_id) {
        $table_map = $_POST['table_map_get'];//nombramos la tabla

        $redisConnection = new RedisConnection();

        // Obtenemos la tabla
        $tableMap=$redisConnection->getTable($table_map);

        var_dump($tableMap);

    }

    public function simulatebattle($tanks, $mapid) {
        // Lógica para simular batalla entre tanques
    }

    public function saveScore($playerId, $score)
    {
        // Lógica para almacenar la puntuación en Redis
        $key = "score:$playerId";
        $this->redis->set($key, $score);
        // ...
    }


    public function getScoreboard($tipe) {
        // Lógica para obtener la tabla de clasificación (diaria, mensual, global)
    }

     
    /*public function getDailyLeaderboard()
    {
        // Lógica para obtener la tabla de clasificación diaria desde Redis
        // ...
        return $leaderboard;
    }

    // Métodos similares para getMonthlyLeaderboard y getGlobalLeaderboard*/
}

?>

