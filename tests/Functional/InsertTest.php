<?php

namespace r\Tests\Functional;

use function r\expr;
use r\Tests\TestCase;

class InsertTest extends TestCase
{
    protected function setUp(): void
    {
        $this->conn = $this->getConnection();
        $this->dataset = $this->useDataset('Heroes');
        $this->dataset->populate();
        $this->opts = [];
    }

    protected function tearDown(): void
    {
        $this->dataset->truncate();
    }

    public function testCustomConflict()
    {
        $res = $this->db()->table('marvel')->insert(
            [
                    'superhero' => 'Iron Man',
                ],
            [
                    'conflict' => function ($x, $k, $o) { return expr(null); },
                ]
        )->run($this->conn, $this->opts);

        $this->assertObStatus(['deleted' => 1], $res);
    }
}
