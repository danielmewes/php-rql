<?php

namespace r\Tests\Functional;

use function r\branch;
use function r\error;
use function r\expr;
use function r\range;
use function r\rDo;
use function r\row;
use r\Tests\TestCase;

class ControlTest extends TestCase
{
    protected function setUp(): void
    {
        $this->conn = $this->getConnection();
        $this->dataset = $this->useDataset('Heroes');
        $this->dataset2 = $this->useDataset('Control');
        $this->dataset->populate();
    }

    protected function tearDown(): void
    {
        $this->dataset->truncate();
        $this->dataset2->truncate();
    }

    public function testDo()
    {
        $this->assertEquals(5.0, rDo([1, 2, 3], function ($x, $y, $z) {
            return $x->mul($y->add($z));
        })->run($this->conn));
    }

    public function testBranchTrue()
    {
        $this->assertEquals('true', branch(expr(true), expr('true'), expr('false'))->run($this->conn));
    }

    public function testBranchFalse()
    {
        $this->assertEquals('false', branch(expr(false), expr('true'), expr('false'))->run($this->conn));
    }

    public function testForEach()
    {
        $db = $this->db();
        $res = expr([1, 2, 3])->rForeach(function ($x) use ($db) {
            return $db->table('t1')->insert(['x' => $x]);
        })->getField('inserted')->run($this->conn);
        $this->assertEquals(3.0, $res);
    }

    public function testMap()
    {
        $this->db()->table('t1')->insert([['x' => 1], ['x' => 2], ['x' => 3]])->run($this->conn);
        $res = $this->db()->table('t1')->map(row('x'))->run($this->conn)->toArray();
        sort($res);
        $this->assertEquals([1.0, 2.0, 3.0], $res);
    }

    public function testError()
    {
        $this->expectException('\r\Exceptions\RqlServerError');
        $this->expectExceptionMessage('Runtime error: ERRRRRR');
        error('ERRRRRR')->run($this->conn);
    }

    public function testToNumber()
    {
        $this->assertEquals(5.0, expr('5.0')->coerceTo('number')->run($this->conn));
    }

    public function testToString()
    {
        $this->assertEquals('5', expr(5.0)->coerceTo('string')->run($this->conn));
    }

    public function testTypeofNumber()
    {
        $this->assertEquals('NUMBER', expr(5.0)->typeOf()->run($this->conn));
    }

    public function testTypeofString()
    {
        $this->assertEquals('STRING', expr('foo')->typeOf()->run($this->conn));
    }

    public function testTypeofNull()
    {
        $this->assertEquals('NULL', expr(null)->typeOf()->run($this->conn));
    }

    public function testTypeofArray()
    {
        $this->assertEquals('ARRAY', expr([1, 2, 3])->typeOf()->run($this->conn));
    }

    public function testTypeofObject()
    {
        $this->assertEquals('OBJECT', expr(['x' => 1])->typeOf()->run($this->conn));
    }

    public function testTableInfo()
    {
        $res = $this->db()->table('marvel')->info()->pluck('type', 'name')->run($this->conn);
        $this->assertEquals(['type' => 'TABLE', 'name' => 'marvel'], (array) $res);
    }

    public function testGetFieldA()
    {
        $this->assertEquals(4.0, expr(['a' => 4])->getField('a')->rDefault(5)->run($this->conn));
    }

    public function testGetFieldB()
    {
        $this->assertEquals(5.0, expr(['a' => 4])->getField('b')->rDefault(5)->run($this->conn));
    }

    public function testGetFieldFunction()
    {
        $this->assertEquals(5.0, expr(['a' => 4])->getField('b')->rDefault(function ($e) {
            return expr(5);
        })->run($this->conn));
    }

    public function testRange()
    {
        $this->assertEquals([0, 1, 2], range(3)->run($this->conn)->toArray());
    }

    public function testSlice()
    {
        $this->assertEquals([1, 2], range(1, 3)->run($this->conn)->toArray());
    }

    public function testRangeLimit()
    {
        $this->assertEquals([0, 1, 2], range()->limit(3)->run($this->conn)->toArray());
    }

    public function testToJson()
    {
        $this->assertEquals('"123"', expr('123')->toJsonString()->run($this->conn));
    }
}
