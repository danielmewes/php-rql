<?php

namespace r\Tests\Functional;

use function r\expr;
use function r\maxval;
use function r\minval;
use r\Tests\TestCase;

class BetweenTest extends TestCase
{
    protected function setUp(): void
    {
        $this->conn = $this->getConnection();
        $this->dataset = $this->useDataset('Heroes');
        $this->dataset->populate();
        $this->db()->table('marvel')->indexCreate('test', function ($x) {
            return expr('5');
        })->run($this->conn);
        $this->db()->table('marvel')->indexWait('test')->run($this->conn);
    }

    protected function tearDown(): void
    {
        $this->db()->table('marvel')->indexDrop('test')->run($this->conn);
        $this->dataset->truncate();
    }

    public function testBetweenIndex()
    {
        $res = $this->db()->table('marvel')->between('5', '5', ['index' => 'test'])->count()->run($this->conn);
        $this->assertEquals(0.0, $res);
    }

    public function testBetweenRightBoundClosed()
    {
        $res = $this->db()->table('marvel')->between('5', '5', ['index' => 'test', 'right_bound' => 'closed'])->count()->run($this->conn);
        $this->assertEquals(3.0, $res);
    }

    public function testBetweenAZ()
    {
        $res = $this->db()->table('marvel')->between('A', 'Z')->count()->run($this->conn);
        $this->assertEquals(3.0, $res);
    }

    public function testBetweenIJ()
    {
        $res = $this->db()->table('marvel')->between('I', 'J')->count()->run($this->conn);
        $this->assertEquals(1.0, $res);
    }

    public function testBetweenII()
    {
        $res = $this->db()->table('marvel')->between('I', 'I')->count()->run($this->conn);
        $this->assertEquals(0.0, $res);
    }

    public function testBetweenMinval()
    {
        $res = $this->db()->table('marvel')->between(minval(), 'J')->count()->run($this->conn);
        $this->assertEquals(1.0, $res);
    }

    public function testBetweenMaxval()
    {
        $res = $this->db()->table('marvel')->between('J', maxval())->count()->run($this->conn);
        $this->assertEquals(2.0, $res);
    }

    public function testBetweenMinMax()
    {
        $res = $this->db()->table('marvel')->between(minval(), maxval())->count()->run($this->conn);
        $this->assertEquals(3.0, $res);
    }
}
