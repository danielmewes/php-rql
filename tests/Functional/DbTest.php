<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;
use r\Exceptions\RqlServerError;

// use function db;
// use function \r\dbCreate;
// use function dbDrop;

class DbTest extends TestCase
{
    public function setUp()
    {
        $this->conn = $this->getConnection();

        $this->db = 'dbTest' . time() . rand(100, 999);
    }

    public function tearDown()
    {
        try {
            \r\dbDrop($this->db)->run($this->conn);
        } catch (RqlServerError $e) {
            // expect deleted db's to fail
            $msg = 'Runtime error: Database `' . $this->db . '` does not exist.';
            if ($e->getMessage() != $msg) {
                throw $e;
            }
        }

    }

    public function testCreate()
    {
        $res = \r\dbCreate($this->db)->pluck('dbs_created')->run($this->conn);

        $this->assertEquals(array('dbs_created' => 1.0), (array)$res);
    }

    public function testWait()
    {
        \r\dbCreate($this->db)->run($this->conn);
        $res = \r\db($this->db)->wait()->run($this->conn);

        $this->assertEquals(array('ready' => 0.0), (array)$res);
    }

    public function testRebalance()
    {
        \r\dbCreate($this->db)->run($this->conn);
        $res = \r\db($this->db)->rebalance()->run($this->conn);

        $this->assertEquals(array(), (array)$res);
    }

    public function testReconfigure()
    {
        \r\dbCreate($this->db)->run($this->conn);
        $res = \r\db($this->db)
            ->reconfigure(array('shards' => 1, 'replicas' => 1))
            ->run($this->conn);

        $this->assertEquals(array(), (array)$res);
    }

    public function testDrop()
    {
        \r\dbCreate($this->db)->run($this->conn);
        $res = \r\dbDrop($this->db)->pluck('dbs_dropped')->run($this->conn);

        $this->assertEquals(array('dbs_dropped' => 1.0), (array)$res);
    }
}
