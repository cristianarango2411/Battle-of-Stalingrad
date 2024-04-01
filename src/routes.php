<?php

use Battle\Model\Battle;
use Battle\Model\Map;
use Battle\Model\Tank;
use Battle\Repository\CouchbaseConnection;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);


$app->get('/api/v1/players', function (Request $request, Response $response, $args){
    $couchbase = new CouchbaseConnection();
    $players=$couchbase->getCollection('players');// get players collection
    $collectionIds=$couchbase->getCollectionIds('players'); // get players collection ids
    $randomKeys = array_rand($collectionIds, 2); // get 2 random keys form players ids
    $playersArray = [];
    $playersArray[] = $players->get($collectionIds[$randomKeys[0]])->content();
    $playersArray[] = $players->get($collectionIds[$randomKeys[1]])->content();
    $jsonResponse=json_encode($playersArray);
    $response->getBody()->write($jsonResponse);
    return $response->withStatus(200);
});

$app->get('/api/v1/tanks', function (Request $request, Response $response, $args){
    $couchbase = new CouchbaseConnection();
    $tanks=$couchbase->getCollection('tanks');//get tanks collection
    $collectionIds=$couchbase->getCollectionIds('tanks');//get tanks collection ids
    $randomKeys = array_rand($collectionIds, 2);//get 2 random keys form tanks ids
    $tanksArray = [];
    $tanksArray[] = $tanks->get($collectionIds[$randomKeys[0]])->content();
    $tanksArray[] = $tanks->get($collectionIds[$randomKeys[1]])->content();
    $jsonResponse=json_encode($tanksArray);
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

    $leaderboardsCollection=$couchbase->getCollection('scores');//get scores collection
    do{
        $index = (string)uniqid(); // Generate a unique ID
    }while($leaderboardsCollection->exists($index)->exists());//check if player exists in leaderboards
    
    $leaderboards = [
        'player_id' => $winner,
        'score' => $battle->getWinnerTank()->getScore(),
        'date' => date('Y-m-d')
    ];
    
    $leaderboardsCollection->upsert($index, $leaderboards);//update scores

    $response->getBody()->write("{score_id: $index, score: ".$battle->getWinnerTank()->getScore()."}");
    return $response->withStatus(200);

});


$app->get('/api/v1/leaderboard', function (Request $request, Response $response, $args){
    $couchbase = new CouchbaseConnection();
    $bucketName = getenv('COUCHBASE_BUCKET');
    $scopeName = getenv('COUCHBASE_SCOPE');
    $leaderboards=$couchbase->getConnection()->query("SELECT player_id, SUM(score) AS score FROM `$bucketName`.`$scopeName`.`scores` GROUP BY player_id Order by score desc limit 5")->rows();
    $jsonResponse=json_encode($leaderboards);
    $response->getBody()->write($jsonResponse);
    return $response->withStatus(200);
});

$app->get('/api/v1/leaderboard/monthly', function (Request $request, Response $response, $args){
    $couchbase = new CouchbaseConnection();
    $bucketName = getenv('COUCHBASE_BUCKET');
    $scopeName = getenv('COUCHBASE_SCOPE');
    $date=date('Y-m-d');
    $date = substr($date, 0, 8)."%";
    $leaderboards=$couchbase->getConnection()->query("SELECT player_id, SUM(score) AS score FROM `$bucketName`.`$scopeName`.`scores` WHERE date Like \"$date\" GROUP BY player_id Order by score desc limit 5")->rows();
    $jsonResponse=json_encode($leaderboards);
    $response->getBody()->write($jsonResponse);
    return $response->withStatus(200);
});

$app->get('/api/v1/leaderboard/daily', function (Request $request, Response $response, $args){
    $couchbase = new CouchbaseConnection();
    $bucketName = getenv('COUCHBASE_BUCKET');
    $scopeName = getenv('COUCHBASE_SCOPE');
    $date=date('Y-m-d');
    $leaderboards=$couchbase->getConnection()->query("SELECT player_id, SUM(score) AS score FROM `$bucketName`.`$scopeName`.`scores` WHERE date = \"$date\" GROUP BY player_id Order by score desc limit 5")->rows();
    $jsonResponse=json_encode($leaderboards);
    $response->getBody()->write($jsonResponse);
    return $response->withStatus(200);
});

$app->get('/api/v1/score/{score_id}', function (Request $request, Response $response, $args) {
    $score_id = $args['score_id'];
    $couchbase = new CouchbaseConnection();
    $scoresCollection=$couchbase->getCollection('scores');//get tanks collection
    if($scoresCollection->exists($score_id)->exists()){//check if score exists
        $jsonResponse=json_encode( $scoresCollection->get($score_id)->content() );//get score by id from coushbase and serialize
    }else{
        $jsonResponse=json_encode( ['error' => 'Score not found'] );
    }
    $response->getBody()->write($jsonResponse);
    return $response->withStatus(200);
});

$app->run();