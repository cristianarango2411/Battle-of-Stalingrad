<?php declare( strict_types = 1 );

require ('vendor/autoload.php');

use Battle\Model\RedisConnection;

$table_name = $_POST['table_name_get'];

$redisConnection = new RedisConnection();

// Guardar la tabla en Redis
$tableContent=$redisConnection->getTable($table_name);

var_dump($tableContent);

//header("Location: index.php");
//exit();
?>



