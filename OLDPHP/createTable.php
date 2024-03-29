<?php declare( strict_types = 1 );

require ('vendor/autoload.php');

use Battle\Repository\RedisConnection;

$table_name = $_POST['table_name'];

echo "<br> Nombre de la tabla: ".$table_name;

$redisConnection = new RedisConnection();

echo "<br> Conecto ";
// Guardar la tabla en Redis
$redisConnection->createTable($table_name);

//header("Location: index.php");
//exit();


