<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;
use function r\expr;
use function r\row;

class AggregationsTest extends TestCase
{
    public function testReduce()
    {
        $this->assertEquals(10.0, expr([1, 2, 3, 4])->reduce(function ($a, $b) {
            return $a->add($b);
        })->run($this->conn));
    }

    public function testCountVal()
    {
        $this->assertEquals(1.0, expr([1, 2, 3, 4])->count(2)->run($this->conn));
    }

    public function testCountRow()
    {
        $this->assertEquals(2.0, expr([1, 2, 3, 4])->count(row()->lt(3))->run($this->conn));
    }

    public function testDistinct()
    {
        $this->assertEquals([1.0, 2.0, 4.0], (array) expr([1, 2, 2, 4])->distinct()->run($this->conn));
    }

    public function testGroupMap()
    {
        $expected = [['reduction' => 1, 'group' => 1], ['reduction' => 4, 'group' => 2], ['reduction' => 4, 'group' => 4]];
        $res = expr([1, 2, 2, 4])->group(function ($r) {
            return $r;
        })->map(function ($r) {
            return $r;
        })->reduce(function ($a, $b) {
            return $a->add($b);
        })->ungroup()->run($this->conn);
        $this->assertEquals($expected, $this->toArray($res));
    }

    public function testGroupCount()
    {
        $expected = [['reduction' => 1, 'group' => 1], ['reduction' => 2, 'group' => 2], ['reduction' => 1, 'group' => 4]];
        $res = expr([['v' => 1], ['v' => 2], ['v' => 2], ['v' => 4]])->group('v')->count()->ungroup()->run($this->conn);
        $this->assertEquals($expected, $this->toArray($res));
    }

    public function testGroupSum()
    {
        $expected = [['reduction' => 1, 'group' => 1], ['reduction' => 4, 'group' => 2], ['reduction' => 4, 'group' => 4]];
        $res = expr([['v' => 1], ['v' => 2], ['v' => 2], ['v' => 4]])->group('v')->sum('v')->ungroup()->run($this->conn);
        $this->assertEquals($expected, $this->toArray($res));
    }

    public function testGroupAvg()
    {
        $expected = [['reduction' => 1, 'group' => 1], ['reduction' => 2, 'group' => 2], ['reduction' => 4, 'group' => 4]];
        $res = expr([['v' => 1], ['v' => 2], ['v' => 2], ['v' => 4]])->group('v')->avg('v')->ungroup()->run($this->conn);
        $this->assertEquals($expected, $this->toArray($res));
    }

    public function testGroupArray()
    {
        $expected = [['reduction' => 1, 'group' => [1, 1]], ['reduction' => 1, 'group' => [2, 2]], ['reduction' => 1, 'group' => [2, 3]], ['reduction' => 1, 'group' => [4, 4]]];
        $res = expr([['v' => 1, 'x' => 1], ['v' => 2, 'x' => 2], ['v' => 2, 'x' => 3], ['v' => 4, 'x' => 4]])->group(['v', 'x'])->count()->ungroup()->run($this->conn);
        $this->assertEquals($expected, $this->toArray($res));
    }

    public function testCount()
    {
        $this->assertEquals(3.0, expr([1, 2, 3])->count()->run($this->conn));
    }

    public function testSum()
    {
        $this->assertEquals(6.0, expr([1, 2, 3])->sum()->run($this->conn));
    }

    public function testAvg()
    {
        $this->assertEquals(2.0, expr([1, 2, 3])->avg()->run($this->conn));
    }

    public function testMax()
    {
        $this->assertEquals(3.0, expr([1, 2, 3])->max()->run($this->conn));
    }

    public function testMin()
    {
        $this->assertEquals(1.0, expr([1, 2, 3])->min()->run($this->conn));
    }

    public function testSumArray()
    {
        $this->assertEquals(6.0, expr([['v' => 1], ['v' => 2], ['v' => 3]])->sum('v')->run($this->conn));
    }

    public function testAvgArray()
    {
        $this->assertEquals(2.0, expr([['v' => 1], ['v' => 2], ['v' => 3]])->avg('v')->run($this->conn));
    }

    public function testMaxArray()
    {
        $this->assertEquals(['v' => 3.0], (array) expr([['v' => 1], ['v' => 2], ['v' => 3]])->max('v')->run($this->conn));
    }

    public function testMinArray()
    {
        $this->assertEquals(['v' => 1.0], (array) expr([['v' => 1], ['v' => 2], ['v' => 3]])->min('v')->run($this->conn));
    }

    public function testContainsA()
    {
        $this->assertTrue(expr(['a', 'b', 'c'])->contains('a')->run($this->conn));
    }

    public function testContainsZ()
    {
        $this->assertFalse(expr(['a', 'b', 'c'])->contains('z')->run($this->conn));
    }

    public function testContainsARow()
    {
        $this->assertTrue(expr(['a', 'b', 'c'])->contains(row()->eq('a'))->run($this->conn));
    }

    public function testContainsZRow()
    {
        $this->assertFalse(expr(['a', 'b', 'c'])->contains(row()->eq('z'))->run($this->conn));
    }

    public function testContainsAFunc()
    {
        $this->assertTrue(expr(['a', 'b', 'c'])->contains(function ($x) {
            return $x->eq('a');
        })->run($this->conn));
    }

    public function testContainsZFunc()
    {
        $this->assertFalse(expr(['a', 'b', 'c'])->contains(function ($x) {
            return $x->eq('z');
        })->run($this->conn));
    }

    public function testDistinctIndex()
    {
        $this->localSetUp();
        $res = $this->db()->table('marvel')->distinct(['index' => 'combatPower'])->run($this->conn);
        $this->assertEquals([2, 5], $res->toArray());
        $this->localTearDown();
    }

    public function testMaxIndex()
    {
        $this->localSetUp();
        $res = $this->db()->table('marvel')->max(['index' => 'combatPower'])->getField('combatPower')->run($this->conn);
        $this->assertEquals(5, $res);
        $this->localTearDown();
    }

    public function testMinIndex()
    {
        $this->localSetUp();
        $res = $this->db()->table('marvel')->min(['index' => 'combatPower'])->getField('combatPower')->run($this->conn);
        $this->assertEquals(2, $res);
        $this->localTearDown();
    }

    protected function localSetUp()
    {
        // require dta
        $this->dataset = $this->useDataset('Heroes');
        $this->dataset->populate();
        // request indexes
        $index = $this->db()->table('marvel')->indexCreate('combatPower')->run($this->conn);
        // wait for index build
        $this->db()->table('marvel')->indexWait('combatPower')->pluck('index', 'ready')->run($this->conn);
    }

    protected function localTearDown()
    {
        $this->db()->table('marvel')->indexDrop('combatPower')->run($this->conn);
        $this->dataset->truncate();
    }
}
