<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

class UpsertTest extends TestCase
{
    public function setUp()
    {
        $this->conn = $this->getConnection();
        $this->data = $this->useDataset('Heroes');
        $this->data->populate();
        $this->opts = array('conflict' => 'replace');
    }

    public function tearDown()
    {
        $this->data->truncate();
    }

    public function testUpsertUnchanged()
    {
        $res = $this->db()->table('marvel')->insert(
            array(
                    'superhero' => 'Iron Man',
                    'superpower' => 'Arc Reactor',
                    'combatPower' => 2.0,
                    'compassionPower' => 1.5
                )
        )->run($this->conn, $this->opts);

        $this->assertObStatus(array('unchanged' => 1), $res);
    }

    public function testUpsertReplaced()
    {
        $res = $this->db()->table('marvel')->insert(
            array(
                    'superhero' => 'Iron Man',
                    'superpower' => 'Suit'
                )
        )->run($this->conn, $this->opts);

        $this->assertObStatus(array('replaced' => 1), $res);
    }

    public function testUpsertInserted()
    {
        $res = $this->db()->table('marvel')->insert(
            array(
                    'superhero' => 'Pepper',
                    'superpower' => 'Stark Industries'
                )
        )->run($this->conn, $this->opts);

        $this->assertObStatus(array('inserted' => 1), $res);
    }
}
