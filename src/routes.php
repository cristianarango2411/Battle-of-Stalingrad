<?php

use Battle\Model\Battle;
use Battle\Model\Map;
use Battle\Model\Tank;
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
    $mapCollection=$couchbase->getCollection('maps');//get maps collection
    $tankCollection=$couchbase->getCollection('tanks');//get tanks collection
    $arrayMap=$mapCollection->get($data['mapid'])->content();//get map by id from coushbase
    $arrayTank1=$tankCollection->get($data['tanks'][0][0])->content();//get first tank by id from coushbase
    $arrayTank2=$tankCollection->get($data['tanks'][0][1])->content();//get second tank by id from coushbase
    $map = Map::fromArray($arrayMap);//create map object
    $tank1 = Tank::fromArray($arrayTank1);//create first tank object
    $tank2 = Tank::fromArray($arrayTank2);//create second tank object

    $battle = new Battle($map);//create battle object    
    $battle->addTank($tank1, $data['players'][0]);//first player and first tank
    $battle->addTank($tank2, $data['players'][1]);//second player and second tank
    $winner=$battle->simulate();//simulate Batlle

    $leaderboardsCollection=$couchbase->getCollection('leaderboards');//get leaderboards collection
    if($leaderboardsCollection->exists($winner)->exists()){//check if player exists in leaderboards
        $arrayLeaderboards=$leaderboardsCollection->get($winner)->content();//get map by id from coushbase
        $score = $arrayLeaderboards['score'];//get score from leaderboards
        $score += $battle->getWinnerTank()->getScore();//add score from first tank
        $leaderboards = [
            'player_id' => $winner,
            'score' => $score
        ];
    }else{//create new player in leaderboards
        $leaderboards = [
            'player_id' => $winner,
            'score' => $battle->getWinnerTank()->getScore()
        ];
    }
    
    $leaderboardsCollection->upsert($winner, $leaderboards);//update leaderboards

    $response->getBody()->write("Battle simulated");
    return $response->withStatus(200);

});

$app->run();