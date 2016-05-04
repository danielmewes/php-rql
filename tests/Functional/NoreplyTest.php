<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

// use function db;

class NoreplyTest extends TestCase
{
    public function setUp()
    {
        $this->conn = $this->getConnection();
        $this->data = $this->useDataset('Control');
    }

    public function testNoReply()
    {
        $this->db()->table('t1')
            ->insert(array('id' => 1, 'key' => 'val'))
            ->run($this->conn, array('noreply' => true));

        $res = $this->db()->table('t1')->getField('key')->run($this->conn);

        $this->assertEquals(array('val'), $res->toArray());
    }
}
