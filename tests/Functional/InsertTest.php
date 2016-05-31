<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

class InsertTest extends TestCase
{
    public function setUp()
    {
        $this->conn = $this->getConnection();
        $this->data = $this->useDataset('Heroes');
        $this->data->populate();
        $this->opts = array();
    }

    public function tearDown()
    {
        $this->data->truncate();
    }

    public function testCustomConflict()
    {
        $res = $this->db()->table('marvel')->insert(
            array(
                    'superhero' => 'Iron Man',
                ),
            array(
                    'conflict' => function($x, $k, $o) { return \r\expr(null); }
                )
        )->run($this->conn, $this->opts);

        $this->assertObStatus(array('deleted' => 1), $res);
    }
}
