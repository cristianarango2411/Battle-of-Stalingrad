<?php declare( strict_types = 1 );

require ('vendor/autoload.php');

use Battle\RedisConnection  ;

$redis = new RedisConnection();

var_dump($redis);