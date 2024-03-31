<?php
require 'vendor/autoload.php';

use Battle\Model\Map;
use Battle\Model\Player;
use Battle\Model\Tank;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;



function makeRequest(&$client,$method, $url, $data = false){
    try {
        if($data){
            $requestData=[
                'body' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ];
            $response = $client->request($method, $url, $requestData);
        }else{
            $response = $client->request($method, $url);
        }
        return $response;
    } catch (RequestException $e) {
        // Exception handling
        fwrite( STDOUT,"There was an error with the request: " . $e->getMessage() );
        die();
    } catch (Exception $e) {
        // Handling any other exceptions
        fwrite( STDOUT, "There was an error: " . $e->getMessage() );
        die();
    }
}





fwrite(STDOUT, "\nWelcome to the Battle of Stalingrad game!\n");

/*fwrite(STDOUT, "\nTo start write your name\n");
$name = fgets(STDIN);
fwrite(STDOUT, "\nName: $name\n");
*/

// Instantiate Game
// Get Players
$client = new Client();
$baseURL = 'http://localhost:8080/api/v1/';
$response = makeRequest($client,'GET', $baseURL.'players');

if($response->getStatusCode() == 200){
    $jsonResponse=$response->getBody()->getContents();
    $objPlayers = json_decode($jsonResponse, true);
    $players = [];
    fwrite(STDOUT, "\nSelected Player:\n");
    foreach($objPlayers as $player){
        $players[] = Player::fromArray($player);
        fwrite(STDOUT, $player['userName']."\n");
    }
}else{
    fwrite(STDOUT, "\nError: We can't connet to the API\n");
    die();
}


// Get Tanks
$response = makeRequest($client,'GET', $baseURL.'tanks');

if($response->getStatusCode() == 200){
    $jsonResponse=$response->getBody()->getContents();
    $objTanks = json_decode($jsonResponse, true);
    $tanks = [];
    fwrite(STDOUT, "\nSelected Tank:\n");
    foreach($objTanks as $tank){
        $tanks[] = Tank::fromArray($tank['tanks']);
        fwrite(STDOUT, $tank['tanks']['name']."\n");
    }
}else{
    fwrite(STDOUT, "\nError: We can't connet to the API\n");
    die();
}

// Get Maps
$response = makeRequest($client,'GET', $baseURL.'maps');

if($response->getStatusCode() == 200){
    $jsonResponse=$response->getBody()->getContents();
    $objMaps = json_decode($jsonResponse, true);
    $maps = [];
    fwrite(STDOUT, "\n\n The following are the map options, type the number to choose 1:\n");
    foreach($objMaps as $key => $map){
        $maps[] = Map::fromArray($map['maps']);
        fwrite(STDOUT, $key.'. '.$map['maps']['name']."\n");
    }  
    $mapIndex = fgets(STDIN);
}else{
    fwrite(STDOUT, "\nError: We can't connet to the API\n");
    die();
}

//data to simulate the Battle

$mapIndex = intval($mapIndex);
$tankIds = [];
foreach($tanks as $tank){
    $tankIds[] = $tank->getId();
}

$data = [
    'tanks' => [
        $tankIds
    ],
    'mapid' => $maps[$mapIndex]->getId(),
    'players' => [
        $players[0]->getId(),
        $players[1]->getId()
    ]
];
$jsonData = json_encode($data);
//fwrite(STDOUT, "\nJson: \n".$jsonData);
//Simulation
$response = makeRequest($client,'POST', $baseURL.'simulate', $jsonData);//Tengo que simular y seguir con el juego
if($response->getStatusCode() == 200){
    $jsonResponse=$response->getBody()->getContents();
    fwrite(STDOUT, "\nResponse: \n".$jsonResponse);
}else{
    fwrite(STDOUT, "\nError: We can't connet to the API\n");
    die();
}


 