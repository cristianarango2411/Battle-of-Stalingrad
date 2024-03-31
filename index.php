<?php declare( strict_types = 1 );

require ('vendor/autoload.php'); 

use Slim\Factory\AppFactory;

require __DIR__.'/src/routes.php';

$app = AppFactory::create();
