<?php

namespace r\Tests\Functional;

use function r\js;
use function r\row;
use r\Tests\TestCase;

class UpdateTest extends TestCase
{
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

    public function testUpdateUpdate()
    {
        $res = $this->db()->table('marvel')->get('Wolverine')->update(['age' => 30])->run($this->conn);
        $this->assertObStatus(['replaced' => 1], $res);
    }

    public function testUpdateViaMethod()
    {
        $res = $this->db()->table('marvel')->update(function ($r) {
            return $r->merge(['age' => 5]);
        })->run($this->conn, $this->opts);
        $this->assertObStatus(['replaced' => 3], $res);
    }

    public function testUpdateRow()
    {
        $this->db()->table('marvel')->update(['age' => 30])->run($this->conn, $this->opts);
        $res = $this->db()->table('marvel')->update(['age' => row('age')->add(1)])->run($this->conn, $this->opts);
        $this->assertObStatus(['replaced' => 3], $res);
    }

    public function testUpdateRowAdd()
    {
        $this->db()->table('marvel')->update(['age' => 30])->run($this->conn, $this->opts);
        $res = $this->db()->table('marvel')->update(['age' => row('age')->add(js('1'))])->run($this->conn, ['durability' => 'soft', 'non_atomic' => true]);
        $this->assertObStatus(['replaced' => 3], $res);
    }
}
