<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

// use function \r\row;
// use function \r\expr;

class TableTest extends TestCase
{
    public function setUp()
    {
        $this->conn = $this->getConnection();
        $this->data = $this->useDataset('Control');
        $this->data->populate();
    }

    public function tearDown()
    {
        $this->data->truncate();
    }

    public function testCreateTable()
    {
        $res = $this->db()->tableCreate(
            't1_' . rand(),
            array('durability' => 'soft', 'primary_key' => 'p')
        )
            ->pluck('tables_created')
            ->run($this->conn);

        $this->assertEquals(array('tables_created' => 1.0), (array)$res);
    }

    public function testInsert()
    {
        $res = $this->db()->table('t1')->insert(array( 'p' => 'foo'))->run($this->conn);

        $this->assertObStatus(array('inserted' => 1), $res);
    }

    public function testRebalance()
    {
        $res = $this->db()->table('t1')->rebalance()->pluck('rebalanced')->run($this->conn);

        $this->assertEquals(array('rebalanced' => 0.0), (array)$res);
    }

    public function testReconfigure()
    {
        $res = $this->db()->table('t1')
            ->reconfigure(array('shards' => 1, 'replicas' => 1))
            ->pluck('reconfigured')
            ->run($this->conn);

        $this->assertEquals(array('reconfigured' => 1.0), (array)$res);
    }

    public function testWait()
    {
        $res = $this->db()->table('t1')->wait()->pluck('ready')->run($this->conn);

        $this->assertEquals(array('ready' => 1.0), (array)$res);
    }

    public function testWaitForAll()
    {
        $res = $this->db()->table('t1')
            ->wait(array('wait_for' => 'all_replicas_ready'))
            ->pluck('ready')
            ->run($this->conn);

        $this->assertEquals(array('ready' => 1.0), (array)$res);
    }

    public function testConfig()
    {
        $res = $this->db()->table('t1')->config()->pluck('name')->run($this->conn);

        $this->assertEquals(array('name' => 't1'), (array)$res);
    }

    public function testStatus()
    {
        $res = $this->db()->table('t1')
            ->status()
            ->getField('status')
            ->pluck('all_replicas_ready')
            ->run($this->conn);

        $this->assertEquals(array('all_replicas_ready' => true), (array)$res);
    }

    public function testTableList()
    {
        // it's hard to know the name of the table crated in testCreateTable(),
        // instead, we assert the array length and the known table

        $res = $this->db()->tableList()->run($this->conn);

        $this->assertCount(7, $res);
        $this->assertContains('t1', (array)$res);
    }

    public function testIndex()
    {
        $res = $this->db()->table('t1')
            ->indexCreate('akey')
            ->run($this->conn);

        $this->assertEquals(array('created' => 1.0), (array)$res);
    }

    public function testIndexRow()
    {
        $res = $this->db()->table('t1')
            ->indexCreate('bfun', \r\row('p'))
            ->run($this->conn);

        $this->assertEquals(array('created' => 1.0), (array)$res);
    }

    public function testIndexFunction()
    {
        $res = $this->db()->table('t1')
            ->indexCreate('cfun', function ($r) {
                return \r\expr(5);
            })
            ->run($this->conn);

        $this->assertEquals(array('created' => 1.0), (array)$res);
    }

    public function testIndexList()
    {
        $res = $this->db()->table('t1')
            ->indexList()
            ->run($this->conn);

        $this->assertEquals(array('akey', 'bfun', 'cfun', 'other'), (array)$res);
    }

    public function testIndexDrop()
    {
        $res = $this->db()->table('t1')
            ->indexDrop('akey')
            ->run($this->conn);

            $this->assertEquals(array('dropped' => 1.0), (array)$res);
    }

    public function testSync()
    {
        $res = $this->db()->table('t1')
            ->sync()
            ->run($this->conn);

        $this->assertEquals(array('synced' => 1.0), (array)$res);
    }

    public function testDontUseOutdated()
    {
        $res = $this->db()->table('t1', array('read_mode' => 'single'))
            ->count()
            ->run($this->conn);

        $this->assertEquals(0.0, $res);
    }

    public function testReadmodeOutdated()
    {
        $res = $this->db()->table('t1', array('read_mode' => 'outdated'))
            ->count()
            ->run($this->conn);

        $this->assertEquals(0.0, $res);
    }

    public function testTableDrop()
    {
        $table = 't2_' . rand();

        $this->db()->tableCreate($table)->run($this->conn);

        $res = $this->db()->tableDrop($table)->pluck('tables_dropped')->run($this->conn);

        $this->assertEquals(array('tables_dropped' => 1.0), (array)$res);
    }
}
