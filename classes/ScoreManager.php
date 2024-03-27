<?php
namespace Battle;
use Predis\Client;

class ScoreManager
 {
    private $scores;
    public $redis;
    

    public function __construct()
    {
        //$this->scores = $scores;
        //connection with redis
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host' => 'redis-17397.c280.us-central1-2.gce.cloud.redislabs.com', 
            'port' => 6379, // The default port of Redis
            'password' => 'STFa6JLfkUu4JksIb53lRxdqwIkPo3Y4', 
        ]);
        

        
    }

    
    

    public function loadTank($tank_id) {
        // Lógica para cargar datos del tanque desde la base de datos
    }

    public function loadMap($map_id) {
        // Lógica para cargar datos del mapa desde la base de datos
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

