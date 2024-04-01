<?php 
declare(strict_types=1);
namespace Battle\Repository;

use Couchbase\Cluster;
use Couchbase\ClusterOptions;
use Couchbase\CollectionInterface;
use Couchbase\ScopeInterface;

class CouchbaseConnection{
    private $cluster;
    private $bucket;
    private $scope;

    public function __construct() {
        $options = new ClusterOptions();
        $options->credentials(getenv('COUCHBASE_USER'), getenv('COUCHBASE_PASSWORD'));
        $options->applyProfile("wan_development");
        $this->cluster = new Cluster(getenv('COUCHBASE_HOST'), $options);
        $this->bucket = $this->cluster->bucket(getenv('COUCHBASE_BUCKET'));
        $this->scope = $this->bucket->scope(getenv('COUCHBASE_SCOPE'));
    }

    public function getConnection(): Cluster{
        return $this->cluster;
    }

    public function getScope(): ScopeInterface{
        return $this->scope;
    }

    public function getCollection(String $collectionName):CollectionInterface{
        return $this->scope->collection($collectionName);
    }

    public function getCollectionIds($collectionName){
        $bucketName = getenv('COUCHBASE_BUCKET');
        $scopeName = getenv('COUCHBASE_SCOPE');
        $query = "SELECT META().id FROM `$bucketName`.`$scopeName`.`$collectionName`";
        $result = $this->cluster->query($query);
        $ids = [];
        foreach ($result->rows() as $row) {
            $ids[] = $row['id'];
        }
        return $ids;
    }

    public function getAllElements($collectionName){
        $bucketName = getenv('COUCHBASE_BUCKET');
        $scopeName = getenv('COUCHBASE_SCOPE');
        $query = "SELECT * FROM `$bucketName`.`$scopeName`.`$collectionName`";
        $result = $this->cluster->query($query);
        $elements = [];
        foreach ($result->rows() as $row) {
            $elements[] = $row;
        }
        return $elements;
    }

    public function openConnection() {
        $options = new ClusterOptions();
        $options->credentials(getenv('COUCHBASE_USER'), getenv('COUCHBASE_PASSWORD'));
        $options->applyProfile("wan_development");
        $this->cluster = new Cluster(getenv('COUCHBASE_HOST'), $options);
        $this->bucket = $this->cluster->bucket(getenv('COUCHBASE_BUCKET'));
        $this->scope = $this->bucket->scope(getenv('COUCHBASE_SCOPE'));
    }

    public function closeConnection() {
        $this->cluster = null;
        $this->bucket = null;
        $this->scope = null;
    }

    public function createCollection($collectionName) {
        // Create a collection in Couchbase
        $this->bucket->collections()->createCollection($collectionName);
    }
    
}
