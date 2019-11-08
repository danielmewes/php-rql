<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

class DeleteTest extends TestCase
{
    protected function setUp(): void
    {
        $this->conn = $this->getConnection();
        $this->dataset = $this->useDataset('Heroes');
        $this->dataset->populate();
    }

    protected function tearDown(): void
    {
        $this->dataset->truncate();
    }

    public function testDelete()
    {
        $res = $this->db()->table('marvel')
            ->get('Wolverine')
            ->delete()
            ->run($this->conn);

        $this->assertObStatus(['deleted' => 1], $res);
    }

    public function testDeleteSoft()
    {
        $res = $this->db()->table('marvel')
            ->get('Wolverine')
            ->delete(['durability' => 'soft'])
            ->run($this->conn);

        $this->assertObStatus(['deleted' => 1], $res);
    }
}
