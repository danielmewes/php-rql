<?php

namespace r\Tests\Functional;

use function r\db;
use function \r\dbCreate;
use function r\dbDrop;
use r\Exceptions\RqlServerError;
use r\Tests\TestCase;


class DbTest extends TestCase
{
    /** @var string */
    protected $db;

    protected function setUp(): void
    {
        $this->conn = $this->getConnection();
        $this->db = 'dbTest'.time().rand(100, 999);
    }

    protected function tearDown(): void
    {
        try {
            dbDrop($this->db)->run($this->conn);
        } catch (RqlServerError $e) {
            // expect deleted db's to fail
            $msg = 'Runtime error: Database `'.$this->db.'` does not exist.';
            if ($e->getMessage() != $msg) {
                throw $e;
            }
        }
    }

    public function testCreate()
    {
        $res = dbCreate($this->db)->pluck('dbs_created')->run($this->conn);
        $this->assertEquals(['dbs_created' => 1.0], (array) $res);
    }

    public function testWait()
    {
        dbCreate($this->db)->run($this->conn);
        $res = db($this->db)->wait()->run($this->conn);
        $this->assertEquals(['ready' => 0.0], (array) $res);
    }

    public function testRebalance()
    {
        dbCreate($this->db)->run($this->conn);
        $res = db($this->db)->rebalance()->run($this->conn);
        $this->assertEquals([], (array) $res);
    }

    public function testReconfigure()
    {
        dbCreate($this->db)->run($this->conn);
        $res = db($this->db)->reconfigure(['shards' => 1, 'replicas' => 1])->run($this->conn);
        $this->assertEquals([], (array) $res);
    }

    public function testDrop()
    {
        dbCreate($this->db)->run($this->conn);
        $res = dbDrop($this->db)->pluck('dbs_dropped')->run($this->conn);
        $this->assertEquals(['dbs_dropped' => 1.0], (array) $res);
    }
}
