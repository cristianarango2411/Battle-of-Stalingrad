<?php 
declare(strict_types=1);
namespace Battle\Repository;

use Exception;
use Predis\Client;
use stdClass;

class RedisConnection implements ConnectionInterface{
    private $redis;

    public function __construct() {
        
        $this->redis = new Client([
            'scheme' => getenv('REDIS_SCHEME'),
            'host' => getenv('REDIS_HOST'), 
            'port' => getenv('REDIS_PORT'),
            'password' => getenv('REDIS_PASSWORD'), 
        ]);
    }

    public function getConnection() {
        return $this->redis;
    }


    public function createTable($tableName) {
        
        if ($this->redis->exists($tableName)) {
            throw new Exception("La tabla $tableName ya existe en Redis.");
        } else {
            // Save the table in Redis
            $object = new stdClass();
            $object->prueba="completada";
            $this->redis->set( $tableName, json_encode( $object ) );
            echo "todo bien";
            $contenido=$this->redis->get($tableName);
            echo "\n tabla contenido: ".$contenido;
        }
    }

    public function deleteTable($tableName) {
        if ($this->redis->exists($tableName)) {
            // Delete the table in redis
            $this->redis->del($tableName);
        } else {
            throw new Exception("La tabla $tableName no existe en Redis.");
        }
    }

    public function getTable($tableName):string {
        if ($this->redis->exists($tableName)) {
            $content=$this->redis->get($tableName);
            return $content;
        }else{
            return "No exixte tabla";
        }
    }


    public function openConnection() {
        // TODO: Implement openConnection() method.
    }

    public function closeConnection() {
        // TODO: Implement closeConnection() method.
    }

    public function create($data) {
        // TODO: Implement create() method.
    }

    public function read($id) {
        // TODO: Implement read() method.
    }

    public function update($id, $data) {
        // TODO: Implement update() method.
    }

    public function delete($id) {
        // TODO: Implement delete() method.
    }
    
}
