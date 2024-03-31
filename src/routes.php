<?php

use Battle\Model\Battle;
use Battle\Repository\CouchbaseConnection;
use Battle\ScoreManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);


$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});

$app->get('/my-endpoint', function (Request $request, Response $response, $args){

    $couchbase = new CouchbaseConnection();
    $collectionIds=$couchbase->getCollectionIds('tanks');
    $jsonResponse=json_encode($collectionIds);
    $response->getBody()->write($jsonResponse);
    return $response->withStatus(200);
});

$app->get('/api/v1/players', function (Request $request, Response $response, $args){
    $couchbase = new CouchbaseConnection();
    $players=$couchbase->getCollection('players');
    $collectionIds=$couchbase->getCollectionIds('players');
    $randomKeys = array_rand($collectionIds, 2);
    $playersArray = [];
    $playersArray[] = $players->get($collectionIds[$randomKeys[0]])->content();
    $playersArray[] = $players->get($collectionIds[$randomKeys[1]])->content();
    $jsonResponse=json_encode($playersArray);
    $response->getBody()->write($jsonResponse);
    return $response->withStatus(200);
});

$app->get('/api/v1/tanks', function (Request $request, Response $response, $args){
    $couchbase = new CouchbaseConnection();
    $elements=$couchbase->getAllElements('tanks');
    $jsonResponse=json_encode($elements);
    $response->getBody()->write($jsonResponse);
    return $response->withStatus(200);
});

$app->get('/api/v1/maps', function (Request $request, Response $response, $args){
    $couchbase = new CouchbaseConnection();
    $elements=$couchbase->getAllElements('maps');
    $jsonResponse=json_encode($elements);
    $response->getBody()->write($jsonResponse);
    return $response->withStatus(200);
});


$app->post('/api/v1/simulate', function (Request $request, Response $response, $args){
    $data = $request->getParsedBody();
    $couchbase = new CouchbaseConnection();
    $mapCollection=$couchbase->getCollection('maps');
    //$map=$mapCollection->get($data['map_id'])->content();


    $response->getBody()->write($data['map_id']);
    return $response->withStatus(200);

    //$battle = new Battle($map);
    // Ahora puedes acceder a los datos del JSON
    //$someValue = $data['someKey'];

    //$response->getBody()->write($jsonResponse);
    //return $response->withStatus(200);
});

$app->run();


/*
$app = new Application();
$app['debug'] = true;


$app->get('/', function() {
    return 'Hello, world!';
});

$app->get('/my-endpoint', function() {
    $couchbase=new CouchbaseConnection();
    //var_dump($couchbase->getConnection());
    return new Response('Hello, Endpoind!', 200);
});

$app->get('/api/v1/tanks/{tank_id}', function($tank_id) use ($app) {
    // Replace this with code to load the tank from the database
    $tank=ScoreManager::loadTank($tank_id);
    return "Tank: " . $tank;
});

// Load game map from database
$app->get('/api/v1/map/{map_id}', function($map_id) use ($app) {
    // Replace this with code to load the map from the database
    return "Map: " . $map_id;
});

// Simulate
$app->get('/api/v1/simulate/', function() use ($app) {
    // Replace this with code to perform the simulation
    return "Simulation";
});

// Display battle score in JSON format
$app->get('/api/v1/score/{score_id}', function($score_id) use ($app) {
    // Replace this with code to load the score from the database and return it in JSON format
    return "Score: " . $score_id;
});

// Daily Leaderboard
$app->get('/api/v1/leaderboard/daily', function() use ($app) {
    // Replace this with code to display the daily leaderboard
    return "Daily Leaderboard";
});

// Monthly Leaderboard
$app->get('/api/v1/leaderboard/monthly', function() use ($app) {
    // Replace this with code to display the monthly leaderboard
    return "Monthly Leaderboard";
});

// Leaderboard
$app->get('/api/v1/leaderboard/', function() use ($app) {
    // Replace this with code to display the leaderboard
    return "Leaderboard";
});

$app->post('/api/v1/score', function(Request $request) use ($app) {
    $score = $request->get('score');
    // Replace this with code to save the score to the database
    return "Score saved: " . $score;
});



$app->run();*/