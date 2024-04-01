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


$bucket = $cluster->bucket(getenv('COUCHBASE_BUCKET'));

var_dump($bucket);

echo "<br><br>COUCHBASE_HOST: ".getenv('COUCHBASE_HOST')."<br>";

echo "COUCHBASE_BUCKET: ".getenv('COUCHBASE_BUCKET')."<br>";
echo "COUCHBASE_SCOPE: ".getenv('COUCHBASE_SCOPE')."<br><br>";
// Create the 'Battle' scope if it doesn't exist
$bucketManager = $bucket->collections();
try {
    $ping=$bucket->ping();
    var_dump($ping);
} catch (Exception $e) {
    echo "Scope already exists.\n";
}
echo "<br><br>";

$bucketName = getenv('COUCHBASE_BUCKET'); // Replace with your bucket name
$scopeName = getenv('COUCHBASE_SCOPE'); // Replace with your scope name

//$collections = ['tanks', 'maps', 'players', 'scores', 'leaderboards'];
$collections = ['tanks'];
foreach ($collections as $collectionName) {
    try {
        $bucketManager->createCollection($scopeName, $collectionName);

        $query = "CREATE PRIMARY INDEX ON `{$bucketName}`.`{$scopeName}`.`{$collectionName}`";
        $cluster->query($query);
        echo "Primary index created successfully.\n";

        $collection=$bucket->scope(getenv('COUCHBASE_SCOPE'))->collection($collectionName);
        echo "Collection: $collectionName <br>";
        var_dump($collection);
        echo "<br><br>";
        
        $elements=[];
        switch ($collectionName) {
            case 'tanks':
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
        $id = "1000",
        $name = "German Panzer IV",
        $health = 100,
        $attack = 70,
        $defense = 50,
        $speed = 1,
        $fuelRange = 200,
        $turretRange = 50
    );
    $tankJson=json_encode($tanks[$i]);
    echo "<br><br>ID:<br>".$tanks[$i]->getId()."<br>";
    echo "".$tankJson."<br><br>";
    $res = $collection->upsert($tanks[$i]->getId(), $tanks[$i]);
    echo "<br>document \"document-key\" has been created with CAS \"%s\"\n". $res->cas();
    // Create a German Panzer V tank
    $i=1;
    $tanks[] = new Tank(
        $id = "1001",
        $name = "German Panzer V",
        $health = 110,
        $attack = 73,
        $defense = 51,
        $speed = 2,
        $fuelRange = 200,
        $turretRange = 54
    );
    $tankJson=json_encode($tanks[$i]);
    echo "<br><br>ID:<br>".$tanks[$i]->getId()."<br>";
    echo "".$tankJson."<br><br>";
    $res = $collection->upsert($tanks[$i]->getId(), $tanks[$i]);
    echo "<br>document \"document-key\" has been created with CAS \"%s\"\n". $res->cas();
    // Create a German Panzer VI tank
    $i=2;
    $tanks[] = new Tank(
        $id = "1002",
        $name = "German Panzer VI",
        $health = 120,
        $attack = 75,
        $defense = 54,
        $speed = 2,
        $fuelRange = 200,
        $turretRange = 55
    );
    $tankJson=json_encode($tanks[$i]);
    echo "<br><br>ID:<br>".$tanks[$i]->getId()."<br>";
    echo "".$tankJson."<br><br>";
    $res = $collection->upsert($tanks[$i]->getId(), $tanks[$i]);
    echo "<br>document \"document-key\" has been created with CAS \"%s\"\n". $res->cas();

    // Create a Soviet T-34 tank
    $tanks[] = new Tank(
        $id = "1003",
        $name = "Soviet T-34",
        $health = 100,
        $attack = 65,
        $defense = 55,
        $speed = 2,
        $fuelRange = 210,
        $turretRange = 45
    );
    $i=3;
    $tankJson=json_encode($tanks[$i]);
    echo "<br><br>ID:<br>".$tanks[$i]->getName()."<br>";
    echo "".$tankJson."<br><br>";
    $res = $collection->upsert($tanks[$i]->getId(), $tanks[$i]);
    echo "<br>document \"document-key\" has been created with CAS \"%s\"\n". $res->cas();

    // Create a Soviet T-35 tank
    $tanks[] = new Tank(
        $id = "1004",
        $name = "Soviet T-35",
        $health = 105,
        $attack = 72,
        $defense = 53,
        $speed = 3,
        $fuelRange = 210,
        $turretRange = 60
    );
    $i=4;
    $tankJson=json_encode($tanks[$i]);
    echo "<br><br>ID:<br>".$tanks[$i]->getName()."<br>";
    echo "".$tankJson."<br><br>";
    $res = $collection->upsert($tanks[$i]->getId(), $tanks[$i]);
    echo "<br>document \"document-key\" has been created with CAS \"%s\"\n". $res->cas();

    // Create a Soviet T-36 tank
    $tanks[] = new Tank(
        $id = "1005",
        $name = "Soviet T-36",
        $health = 120,
        $attack = 74,
        $defense = 50,
        $speed = 3,
        $fuelRange = 210,
        $turretRange = 63
    );
    $i=5;
    $tankJson=json_encode($tanks[$i]);
    echo "<br><br>ID:<br>".$tanks[$i]->getName()."<br>";
    echo "".$tankJson."<br><br>";
    $res = $collection->upsert($tanks[$i]->getId(), $tanks[$i]);
    echo "<br>document \"document-key\" has been created with CAS \"%s\"\n". $res->cas();
    // Create a Soviet T-37 tank
    $tanks[] = new Tank(
        $id = "1006",
        $name = "Soviet T-37",
        $health = 118,
        $attack = 75,
        $defense = 52,
        $speed = 3,
        $fuelRange = 210,
        $turretRange = 60
    );
    $i=5;
    $tankJson=json_encode($tanks[$i]);
    echo "<br><br>ID:<br>".$tanks[$i]->getName()."<br>";
    echo "".$tankJson."<br><br>";
    $res = $collection->upsert($tanks[$i]->getId(), $tanks[$i]);
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
        $collection->upsert($map->getId(), $map);
    }
    return $maps;
}