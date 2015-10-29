<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

class DeleteTest extends TestCase
{
    public function setUp()
    {
        $this->conn = $this->getConnection();
        $this->data = $this->useDataset('Heroes');
        $this->data->populate();
    }

    public function tearDown()
    {
        $this->data->truncate();
    }

    public function testDelete()
    {
        $res = $this->db()->table('marvel')
            ->get('Wolverine')
            ->delete()
            ->run($this->conn);

        $this->assertObStatus(array('deleted' => 1), $res);
    }

    public function testDeleteSoft()
    {
        $res = $this->db()->table('marvel')
            ->get('Wolverine')
            ->delete(array('durability' => 'soft'))
            ->run($this->conn);

        $this->assertObStatus(array('deleted' => 1), $res);
    }
}
