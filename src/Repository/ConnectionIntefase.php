<?php
declare(strict_types=1);
namespace Battle\Repository;

interface ConnectionInterface {
    public function __construct();
    public function createTable($tableName);
    public function deleteTable($tableName);
    public function openConnection();
    public function closeConnection();
    public function getConnection();
    public function create($table, $data);
    public function read($table, $id);
    public function update($table, $id, $data);
    public function delete($table, $id);
}