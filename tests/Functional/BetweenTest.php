<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

// use function \r\expr;
// use function \r\maxval;
// use function \r\minval;

class BetweenTest extends TestCase
{
    public function setUp()
    {
        $this->conn = $this->getConnection();
        $this->data = $this->useDataset('Heroes');
        $this->data->populate();
        $this->db()->table('marvel')
            ->indexCreate('test', function ($x) {
                return \r\expr('5');
            })
            ->run($this->conn);
        $this->db()->table('marvel')->indexWait('test')->run($this->conn);
    }

    public function tearDown()
    {
        $this->db()->table('marvel')->indexDrop('test')->run($this->conn);
        $this->data->truncate();
    }

    public function testBetweenIndex()
    {
        $res = $this->db()->table('marvel')
            ->between('5', '5', array('index' => 'test'))
            ->count()
            ->run($this->conn);

        $this->assertEquals(0.0, $res);
    }

    public function testBetweenRightBoundClosed()
    {
        $res = $this->db()->table('marvel')
            ->between('5', '5', array('index' => 'test', 'right_bound' => 'closed'))
            ->count()
            ->run($this->conn);

        $this->assertEquals(3.0, $res);
    }

    public function testBetweenAZ()
    {
        $res = $this->db()->table('marvel')
            ->between('A', 'Z')
            ->count()
            ->run($this->conn);

        $this->assertEquals(3.0, $res);
    }

    public function testBetweenIJ()
    {
        $res = $this->db()->table('marvel')
            ->between('I', 'J')
            ->count()
            ->run($this->conn);

        $this->assertEquals(1.0, $res);
    }

    public function testBetweenII()
    {
        $res = $this->db()->table('marvel')
            ->between('I', 'I')
            ->count()
            ->run($this->conn);

        $this->assertEquals(0.0, $res);
    }

    public function testBetweenMinval()
    {
        $res = $this->db()->table('marvel')
            ->between(\r\minval(), 'J')
            ->count()
            ->run($this->conn);

        $this->assertEquals(1.0, $res);
    }

    public function testBetweenMaxval()
    {
        $res = $this->db()->table('marvel')
            ->between('J', \r\maxval())
            ->count()
            ->run($this->conn);

        $this->assertEquals(2.0, $res);
    }

    public function testBetweenMinMax()
    {
        $res = $this->db()->table('marvel')
            ->between(\r\minval(), \r\maxval())
            ->count()
            ->run($this->conn);

        $this->assertEquals(3.0, $res);
    }
}
