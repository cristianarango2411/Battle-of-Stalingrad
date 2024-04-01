<?php
require 'vendor/autoload.php';

use Battle\Model\Map;
use Battle\Model\Player;
use Battle\Model\Tank;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$client = new Client();
$baseURL = 'http://localhost:8080/api/v1/';

$continue = true;
while($continue){

    fwrite(STDOUT, "\nWelcome to the Battle of Stalingrad game!\n");

    //Menu  1. Start Game 2. leaderboards 3. Exit
    fwrite(STDOUT, "\nMenu  \n1. Start Game \n2. Battle Score \n3. General Leaderboard \n4. Monthly Leaderboard \n5. Daily Leaderboard \n6. Exit\n");
    $option = fgets(STDIN);

    switch($option){
        case 1:
            game($client, $baseURL);
            break;
        case 2:
            getScore($client, $baseURL);
            break;
        case 3:
            leaderboards($client, $baseURL);
            break;
        case 4:
            leaderboards($client, $baseURL, 'Monthly');
            break;
        case 5:
            leaderboards($client, $baseURL, 'Daily');
            break;
        case 6:
            fwrite(STDOUT, "\nExit.\n");
            $continue = false;
            break;
        default:
            fwrite(STDOUT, "\nInvalid option\n");
            break;
    }

}

function getScore(&$client, $baseURL){
    fwrite(STDOUT, "\nPlease enter the score ID\n");
    $scoreID = fgets(STDIN);

    $response = makeRequest($client,'GET', $baseURL.'score/'.$scoreID);

    if($response->getStatusCode() == 200){
        $jsonResponse=$response->getBody()->getContents();
        fwrite(STDOUT, $jsonResponse."\n");
    }else{
        fwrite(STDOUT, "\nError: We can't connet to the API\n");
        die();
    }

}

function leaderboards(&$client, $baseURL, $type = 'General'){
    // Get Players

    switch($type){
        case 'General':
            $response = makeRequest($client,'GET', $baseURL.'leaderboard');
            break;
        case 'Monthly':
            $response = makeRequest($client,'GET', $baseURL.'leaderboard/monthly');
            break;
        case 'Daily':
            $response = makeRequest($client,'GET', $baseURL.'leaderboard/daily');
            break;
        default:
            fwrite(STDOUT, "\nError: Invalid type\n");
            die();
            break;
    }
    

    if($response->getStatusCode() == 200){
        $jsonResponse=$response->getBody()->getContents();
        $objLeaderboard = json_decode($jsonResponse, true);
        
        //$leaderboards = [];
        fwrite(STDOUT, "\n\n$type Leaderboard:\n");
        foreach($objLeaderboard as $key => $leaderboard){
            $position = $key+1;
            $leaderboardTxt = $position.". ".$leaderboard['player_id']." - ".$leaderboard['score'];
            fwrite(STDOUT, $leaderboardTxt."\n");
        }
        fwrite(STDOUT, "\n\n");
    }else{
        fwrite(STDOUT, "\nError: We can't connet to the API\n");
        die();
    }

}


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


function game(&$client, $baseURL){
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
        fwrite(STDOUT, "\nSelected Tank:\n\n");
        foreach($objTanks as $tank){
            $tanks[] = Tank::fromArray($tank);
            fwrite(STDOUT, $tank['name']."\n");
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

    //Simulation
    $response = makeRequest($client,'POST', $baseURL.'simulate', $jsonData);//simulate the battle
    if($response->getStatusCode() == 200){
        $jsonResponse=$response->getBody()->getContents();
        fwrite(STDOUT, "\nResponse: \n".$jsonResponse);
    }else{
        fwrite(STDOUT, "\nError: We can't connet to the API\n");
        die();
    }
    fwrite(STDOUT, "\n\n");

}

 