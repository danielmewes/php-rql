<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

class NoreplyTest extends TestCase
{
    protected function setUp(): void
    {
        $this->conn = $this->getConnection();
        $this->dataset = $this->useDataset('Control');
    }

    public function testNoReply()
    {
        $this->db()->table('t1')
            ->insert(['id' => 1, 'key' => 'val'])
            ->run($this->conn, ['noreply' => true]);

        $res = $this->db()->table('t1')->getField('key')->run($this->conn);

        $this->assertEquals(['val'], $res->toArray());
    }
}
