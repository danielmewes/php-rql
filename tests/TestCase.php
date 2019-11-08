<?php

namespace r\Tests;

use r\Connection;
use r\Queries\Dbs\Db;
use r\Tests\Datasets\Dataset;
use function r\connect;
use function r\db;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var array<string, Dataset> */
    protected $datasets = [];

    /** @var Dataset */
    protected $dataset;

    /** @var Connection */
    protected $conn;

    protected function setUp(): void
    {
        $this->conn = $this->getConnection();
    }

    // return the current db connection
    protected function getConnection(): Connection
    {
        static $connection;

        if (!isset($connection)) {
            $connection = connect(getenv('RDB_HOST'), getenv('RDB_PORT'), getenv('RDB_DB'));
        }

        return $connection;
    }

    // enable $this->db(), instead of \rdb('DB_NAME'), in tests
    protected function db(): Db
    {
        return db(getenv('RDB_DB'));
    }

    // returns the requested dataset
    protected function useDataset($name)
    {
        static $datasets;

        if (!isset($datasets[$name])) {
            $ds = 'r\Tests\Datasets\\'.$name;
            $datasets[$name] = new $ds($this->conn);
        }

        return $datasets[$name];
    }

    // test the results status
    protected function assertObStatus($status, $data)
    {
        $statuses = [
            'unchanged',
            'skipped',
            'replaced',
            'inserted',
            'errors',
            'deleted',
        ];

        foreach ($statuses as $s) {
            $status[$s] = isset($status[$s]) ? $status[$s] : 0;
        }

        $data->setFlags($data::ARRAY_AS_PROPS);

        foreach ($statuses as $s) {
            $res[$s] = $data->$s ?? 0;
        }

        $this->assertEquals($status, $res);
    }

    // convert a results objects (usually ArrayObject) to an array
    // works on multidimensional arrays, too
    protected function toArray($object)
    {
        return json_decode(json_encode($object), true);
    }
}
