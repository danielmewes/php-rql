<?php

namespace r\Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $datasets = array();

    public function setUp()
    {
        $this->conn = $this->getConnection();
    }

    // return the current db connection
    protected function getConnection()
    {
        static $connection;

        if (!isset($connection)) {
            $connection = \r\connect(getenv('RDB_HOST'), getenv('RDB_PORT'), getenv('RDB_DB'));
        }

        return $connection;
    }

    // enable $this->db(), instead of \rdb('DB_NAME'), in tests
    protected function db()
    {
        return \r\db(getenv('RDB_DB'));
    }

    // returns the requested dataset
    protected function useDataset($name)
    {
        static $datasets;

        if (!isset($datasets[$name])) {
            $ds = 'r\Tests\Datasets\\' . $name;
            $datasets[$name] = new $ds($this->conn);
        }

        return $datasets[$name];
    }

    // test the results status
    protected function assertObStatus($status, $data)
    {
        $statuses =  array(
            'unchanged',
            'skipped',
            'replaced',
            'inserted',
            'errors',
            'deleted'
        );

        foreach ($statuses as $s) {
            $status[$s] = isset($status[$s]) ? $status[$s] : 0;
        }


        $data->setFlags($data::ARRAY_AS_PROPS);

        foreach ($statuses as $s) {
            $res[$s] = isset($data->$s) ? $data->$s : 0;
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
