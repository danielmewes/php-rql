<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

// use function \r\expr;

class GetTest extends TestCase
{
    public function setUp()
    {
        $this->conn = $this->getConnection();
        $this->data = $this->useDataset('Heroes');
        $this->data->populate();

        $this->db()->table('marvel')->indexCreate(
            'test',
            function ($x) {
                return \r\expr('5');
            }
        )->run($this->conn);

        $this->db()->table('marvel')->indexWait('test')->run($this->conn);
    }

    public function tearDown()
    {
        $this->db()->table('marvel')->indexDrop('test')->run($this->conn);
        $this->data->truncate();
    }

    public function testGetAllIndex()
    {
        $res = $this->db()->table('marvel')
            ->getAll('5', array('index' => 'test'))
            ->count()
            ->run($this->conn);

        $this->assertEquals(3.0, $res);
    }

    public function testGetAll()
    {
        $res = $this->db()->table('marvel')
            ->getAll('Iron Man')
            ->count()
            ->run($this->conn);

        $this->assertEquals(1.0, $res);
    }

    public function testGetMultiple()
    {
        $res = $this->db()->table('marvel')
            ->getMultiple(array('Iron Man', 'Wolverine'))
            ->count()
            ->run($this->conn);

        $this->assertEquals(2.0, $res);
    }

    public function testGet()
    {
        $res = $this->db()->table('marvel')
            ->get('Iron Man')
            ->run($this->conn);

        $this->assertEquals(
            array(
                'superhero' => 'Iron Man',
                'superpower' => 'Arc Reactor',
                'combatPower' => 2.0,
                'compassionPower' => 1.5
            ),
            (array)$res
        );
    }
}
