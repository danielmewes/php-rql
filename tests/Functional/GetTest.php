<?php

namespace r\Tests\Functional;

use function r\expr;
use r\Tests\TestCase;

class GetTest extends TestCase
{
    protected function setUp(): void
    {
        $this->conn = $this->getConnection();
        $this->dataset = $this->useDataset('Heroes');
        $this->dataset->populate();

        $this->db()->table('marvel')->indexCreate(
            'test',
            function ($x) {
                return expr('5');
            }
        )->run($this->conn);

        $this->db()->table('marvel')->indexWait('test')->run($this->conn);
    }

    protected function tearDown(): void
    {
        $this->db()->table('marvel')->indexDrop('test')->run($this->conn);
        $this->dataset->truncate();
    }

    public function testGetAllIndex()
    {
        $res = $this->db()->table('marvel')
            ->getAll('5', ['index' => 'test'])
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
            ->getMultiple(['Iron Man', 'Wolverine'])
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
            [
                'superhero' => 'Iron Man',
                'superpower' => 'Arc Reactor',
                'combatPower' => 2.0,
                'compassionPower' => 1.5,
            ],
            (array) $res
        );
    }
}
