<?php
namespace Battle\Model;

use Exception;
use Predis\Client;
use stdClass;

class RedisConnection {
    private $redis;

    public function __construct() {
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host' => 'redis-17397.c280.us-central1-2.gce.cloud.redislabs.com', 
            'port' => 17397, // The default port of Redis
            'password' => 'STFa6JLfkUu4JksIb53lRxdqwIkPo3Y4', 
        ]);
    }

    public function getConnection() {
        return $this->redis;
    }


    public function createTable($table_name) {
        
        if ($this->redis->exists($table_name)) {
            throw new Exception("La tabla $table_name ya existe en Redis.");
        } else {
            // Save the table in Redis
            $object = new stdClass();
            $object->prueba="completada";
            $this->redis->set( $table_name, json_encode( $object ) );
            echo "todo bien";
            $contenido=$this->redis->get($table_name);
            echo "\n tabla contenido: ".$contenido;
        }
    }

    public function deleteTable($table_name) {
        if ($this->redis->exists($table_name)) {
            // Delete the table in redis
            $this->redis->del($table_name);
        } else {
            throw new Exception("La tabla $table_name no existe en Redis.");
        }
    }

    public function getTable($table_name) {
        if ($this->redis->exists($table_name)) {
            $content=$this->redis->get($table_name);
            return json_decode($content);
        }else{
            return "No exixte tabla";
        }
    }
}
?>


        
