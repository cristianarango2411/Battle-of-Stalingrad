<?php declare( strict_types = 1 );

require ('vendor/autoload.php');


use Battle\Repository\RedisConnection;

$redisConnection = new RedisConnection();

$table_name = $_POST['table_name_delete'];

// Eliminar la tabla de Redis
$redisConnection->deleteTable($table_name);

//header("Location: index.php");
//exit();