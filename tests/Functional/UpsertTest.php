<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

class UpsertTest extends TestCase
{
    /** @var array */
    protected $opts;

    protected function setUp(): void
    {
        $this->conn = $this->getConnection();
        $this->dataset = $this->useDataset('Heroes');
        $this->dataset->populate();
        $this->opts = ['conflict' => 'replace'];
    }

    protected function tearDown(): void
    {
        $this->dataset->truncate();
    }

    public function testUpsertUnchanged()
    {
        $res = $this->db()->table('marvel')->insert(
            [
                    'superhero' => 'Iron Man',
                    'superpower' => 'Arc Reactor',
                    'combatPower' => 2.0,
                    'compassionPower' => 1.5,
                ]
        )->run($this->conn, $this->opts);

        $this->assertObStatus(['unchanged' => 1], $res);
    }

    public function testUpsertReplaced()
    {
        $res = $this->db()->table('marvel')->insert(
            [
                    'superhero' => 'Iron Man',
                    'superpower' => 'Suit',
                ]
        )->run($this->conn, $this->opts);

        $this->assertObStatus(['replaced' => 1], $res);
    }

    public function testUpsertInserted()
    {
        $res = $this->db()->table('marvel')->insert(
            [
                    'superhero' => 'Pepper',
                    'superpower' => 'Stark Industries',
                ]
        )->run($this->conn, $this->opts);

        $this->assertObStatus(['inserted' => 1], $res);
    }
}
