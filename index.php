<?php declare( strict_types = 1 );

require ('vendor/autoload.php'); 


$app = new Silex\Application();

require __DIR__.'/src/routes.php';

$app->run();
