<?php
declare( strict_types = 1 );

require_once 'vendor/autoload.php';

use Battle\Model\Map;
use Battle\Model\Obstacle;
use Battle\Model\Tank;
use Battle\Model\Player;
use Couchbase\Cluster;
use Couchbase\ClusterOptions;
use Couchbase\InsertOptions;

$options = new ClusterOptions();
$options->credentials(getenv('COUCHBASE_USER'), getenv('COUCHBASE_PASSWORD'));
$options->applyProfile("wan_development");
$opts = new InsertOptions();
$opts->timeout(10000 /* milliseconds */);

$cluster = new Cluster(getenv('COUCHBASE_HOST'), $options );


/*try {
    $cluster->buckets()->getBucket(getenv('COUCHBASE_BUCKET'));
} catch (Exception $ex) {
    echo $ex->getMessage();
}*/ 

$bucket = $cluster->bucket(getenv('COUCHBASE_BUCKET'));

var_dump($bucket);

echo "<br><br>COUCHBASE_HOST: ".getenv('COUCHBASE_HOST')."<br>";

echo "COUCHBASE_BUCKET: ".getenv('COUCHBASE_BUCKET')."<br>";
echo "COUCHBASE_SCOPE: ".getenv('COUCHBASE_SCOPE')."<br><br>";
// Create the 'Battle' scope if it doesn't exist
$bucketManager = $bucket->collections();
try {
    //$bucketManager->createScope(getenv('COUCHBASE_SCOPE'));
    $ping=$bucket->ping();
    var_dump($ping);
} catch (Exception $e) {
    echo "Scope already exists.\n";
}
echo "<br><br>";


// Create the 'tanks', 'maps', 'players', 'scores', and 'leaderboards' collections
//$collections = ['tanks', 'maps', 'players', 'scores', 'leaderboards'];


$bucketName = getenv('COUCHBASE_BUCKET'); // Replace with your bucket name
$scopeName = getenv('COUCHBASE_SCOPE'); // Replace with your scope name
$collectionName = 'tanks'; // Replace with your collection name

//$cluster = new \Couchbase\Cluster(getenv('COUCHBASE_HOST'), $options);
//$query = "CREATE PRIMARY INDEX ON `{$bucketName}`.`{$scopeName}`.`{$collectionName}`";
//$cluster->query($query);

//echo "Primary index created successfully.\n";
//$collections = ['tanks', 'maps', 'players', 'scores', 'leaderboards'];
$collections = ['tanks', 'maps', 'players'];
foreach ($collections as $collectionName) {
    try {
        //$bucketManager->createCollection($scopeName, $collectionName);

        $collection=$bucket->scope(getenv('COUCHBASE_SCOPE'))->collection($collectionName);
        echo "Collection: $collectionName <br>";
        var_dump($collection);
        echo "<br><br>";
        
        $elements=[];
        switch ($collectionName) {
            case 'tanks':
                //$collection->remove("German_Panzer");
                //$collection->upsert("German_Panzer", '{"text": "prueba"}');
                //$elements=createTanks($collection, $opts);
                break;
            case 'maps':
                //$elements=createMaps($collection, $opts);
                break;
            case 'players':
                //$elements=createPlayers($collection, $opts);
                break;
            default:
                break;
        }
        /*foreach ($elements as $key => $element) {
            echo "<br><br>Element created: $element->getId()<br>";
            var_dump( $collection->get((string)$element->getId()) );
        }*/
        
    } catch (Exception $ex) {
        // The collection probably already exists, so we can ignore this error
        echo $ex->getMessage();
    }
}


function createTanks(&$collection, $opts) {
    $tanks=[];
    // Create a German Panzer IV tank
    $i=0;
    $tanks[] = new Tank(
        $id = "German_Panzer_IV",
        $name = "German Panzer IV",
        $health = 100,
        $speed = 30,
        $fuelRange = 200,
        $turretRange = 50,
        $attack = 70,
        $defense = 50
    );
    $tankJson=json_encode($tanks[$i]);
    echo "<br><br>ID:<br>".$tanks[$i]->getId()."<br>";
    echo "".$tankJson."<br><br>";
    $res = $collection->upsert($tanks[$i]->getId(), $tankJson);
    echo "<br>document \"document-key\" has been created with CAS \"%s\"\n". $res->cas();

    // Create a Soviet T-34 tank
    $tanks[] = new Tank(
        $id = "Soviet_T-34",
        $name = "Soviet T-34",
        $health = 100,
        $speed = 32,
        $fuelRange = 210,
        $turretRange = 45,
        $attack = 65,
        $defense = 55
    );
    $i=1;
    $tankJson=json_encode($tanks[$i]);
    echo "<br><br>ID:<br>".$tanks[$i]->getName()."<br>";
    echo "".$tankJson."<br><br>";
    $res = $collection->upsert($tanks[$i]->getId(), $tankJson);
    echo "<br>document \"document-key\" has been created with CAS \"%s\"\n". $res->cas();
    return $tanks;
}

function createPlayers(&$collection, $opts) {
    $players = [];
    for ($i = 0; $i < 50; $i++) {
        $id = (string)uniqid(); // Generate a unique ID
        $userName = "Player_" . ($i + 1);
        $player = new Player($userName, $id);
        $players[] = $player;
        $collection->upsert($player->getId(), $player);
    }
    return $players;
}

function createMaps(&$collection, &$opts) {
    $maps = [];
    for ($i = 0; $i < 10; $i++) {
        $id = (string)uniqid(); // Generate a unique ID
        $mapName = "Map_" . ($i + 1);
        $width = rand(51, 75); // Generate a random number between 51 and 100 for the width
        $height = rand(51, 75); // Generate a random number between 51 and 100 for the height

        // Create an array of Obstacle objects with random positions
        $obstacles = [];
        for ($j = 0; $j < rand(4, 10); $j++) {
            $obstacleX = rand(0, $width - 1);
            $obstacleY = rand(0, $height - 1);
            $obstacles[] = new Obstacle($obstacleX, $obstacleY);
        }

        $map = new Map($id, $mapName, $width, $height, $obstacles);
        $maps[] = $map;
        $collection->insert($map->getId(), $map, $opts);
    }
    return $maps;
}