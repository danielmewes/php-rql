<?php

namespace r\Tests\Functional;

use function r\expr;
use function r\row;
use r\Tests\TestCase;

class TableTest extends TestCase
{
    protected function setUp(): void
    {
        $this->conn = $this->getConnection();
        $this->dataset = $this->useDataset('Control');
        $this->dataset->populate();
    }

    protected function tearDown(): void
    {
        $this->dataset->truncate();
    }

    public function testCreateTable()
    {
        $res = $this->db()->tableCreate(
            't1_'.rand(),
            ['durability' => 'soft', 'primary_key' => 'p']
        )
            ->pluck('tables_created')
            ->run($this->conn);

        $this->assertEquals(['tables_created' => 1.0], (array) $res);
    }

    public function testInsert()
    {
        $res = $this->db()->table('t1')->insert(['p' => 'foo'])->run($this->conn);

        $this->assertObStatus(['inserted' => 1], $res);
    }

    public function testRebalance()
    {
        $res = $this->db()->table('t1')->rebalance()->pluck('rebalanced')->run($this->conn);

        $this->assertEquals(['rebalanced' => 0.0], (array) $res);
    }

    public function testReconfigure()
    {
        $res = $this->db()->table('t1')
            ->reconfigure(['shards' => 1, 'replicas' => 1])
            ->pluck('reconfigured')
            ->run($this->conn);

        $this->assertEquals(['reconfigured' => 1.0], (array) $res);
    }

    public function testWait()
    {
        $res = $this->db()->table('t1')->wait()->pluck('ready')->run($this->conn);

        $this->assertEquals(['ready' => 1.0], (array) $res);
    }

    public function testWaitForAll()
    {
        $res = $this->db()->table('t1')
            ->wait(['wait_for' => 'all_replicas_ready'])
            ->pluck('ready')
            ->run($this->conn);

        $this->assertEquals(['ready' => 1.0], (array) $res);
    }

    public function testConfig()
    {
        $res = $this->db()->table('t1')->config()->pluck('name')->run($this->conn);

        $this->assertEquals(['name' => 't1'], (array) $res);
    }

    public function testStatus()
    {
        $res = $this->db()->table('t1')
            ->status()
            ->getField('status')
            ->pluck('all_replicas_ready')
            ->run($this->conn);

        $this->assertEquals(['all_replicas_ready' => true], (array) $res);
    }

    public function testTableList()
    {
        $res = $this->db()->tableList()->run($this->conn);

        $this->assertCount(7, $res);
    }

    public function testIndex()
    {
        $res = $this->db()->table('t1')
            ->indexCreate('akey')
            ->run($this->conn);

        $this->assertEquals(['created' => 1.0], (array) $res);
    }

    public function testIndexRow()
    {
        $res = $this->db()->table('t1')
            ->indexCreate('bfun', row('p'))
            ->run($this->conn);

        $this->assertEquals(['created' => 1.0], (array) $res);
    }

    public function testIndexFunction()
    {
        $res = $this->db()->table('t1')
            ->indexCreate('cfun', function ($r) {
                return expr(5);
            })
            ->run($this->conn);

        $this->assertEquals(['created' => 1.0], (array) $res);
    }

    public function testIndexList()
    {
        $res = $this->db()->table('t1')
            ->indexList()
            ->run($this->conn);

        $this->assertEquals(['akey', 'bfun', 'cfun', 'other'], (array) $res);
    }

    public function testIndexDrop()
    {
        $res = $this->db()->table('t1')
            ->indexDrop('akey')
            ->run($this->conn);

        $this->assertEquals(['dropped' => 1.0], (array) $res);
    }

    public function testSync()
    {
        $res = $this->db()->table('t1')
            ->sync()
            ->run($this->conn);

        $this->assertEquals(['synced' => 1.0], (array) $res);
    }

    public function testDontUseOutdated()
    {
        $res = $this->db()->table('t1', ['read_mode' => 'single'])
            ->count()
            ->run($this->conn);

        $this->assertEquals(0.0, $res);
    }

    public function testReadmodeOutdated()
    {
        $res = $this->db()->table('t1', ['read_mode' => 'outdated'])
            ->count()
            ->run($this->conn);

        $this->assertEquals(0.0, $res);
    }

    public function testTableDrop()
    {
        $table = 't2_'.rand();

        $this->db()->tableCreate($table)->run($this->conn);

        $res = $this->db()->tableDrop($table)->pluck('tables_dropped')->run($this->conn);

        $this->assertEquals(['tables_dropped' => 1.0], (array) $res);
    }
}
