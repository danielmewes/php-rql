<?php

namespace r\Tests\Functional;

use function \r\row;
use function \r\expr;
use function \r\range;
use function \r\branch;
use function \r\mapMultiple;
use r\Tests\TestCase;

class MapTest extends TestCase
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

    public function testMap()
    {
        $x = 1;
        $res = $this->db()->table('marvel')->map(function ($hero) {
            return $hero('combatPower')->add($hero('compassionPower')->mul(2));
        })->run($this->conn);
        $this->assertEquals([7.0, 9.0, 5.0], $res->toArray());
    }

    public function testMapRow()
    {
        $res = $this->db()->table('marvel')->map(row('combatPower')->add(row('compassionPower')->mul(2)))->run($this->conn);
        $this->assertEquals([7.0, 9.0, 5.0], $res->toArray());
    }

    public function testCoerceToMapFunc()
    {
        $res = expr([$this->db()->table('marvel')->coerceTo('array'), $this->db()->table('marvel')->coerceTo('array')])->concatMap(function ($hero) {
            return $hero->pluck('superhero');
        })->map(row('superhero'))->run($this->conn);
        $this->assertEquals(['Spiderman', 'Wolverine', 'Iron Man', 'Spiderman', 'Wolverine', 'Iron Man'], (array) $res);
    }

    public function testCoerceToMap()
    {
        $res = expr([$this->db()->table('marvel')->coerceTo('array'), $this->db()->table('marvel')->coerceTo('array')])->concatMap(row()->pluck('superhero'))->map(row('superhero'))->run($this->conn);
        $this->assertEquals(['Spiderman', 'Wolverine', 'Iron Man', 'Spiderman', 'Wolverine', 'Iron Man'], (array) $res);
    }

    public function testRegression62()
    {
        $res = expr([1, 2, 3])->map(branch(expr(true), function ($x) {
            return $x;
        }, function ($x) {
            return $x;
        }))->run($this->conn);
        $this->assertEquals([1.0, 2.0, 3.0], (array) $res);
    }

    public function testMapMultipleRange()
    {
        $res = mapMultiple([range(1, 4), range(2, 5)], function ($x, $y) {
            return $x->add($y);
        })->run($this->conn);
        $this->assertEquals([3, 5, 7], $res->toArray());
    }

    public function tesRangetMapMultiple()
    {
        $res = range(1, 4)->mapMultiple([range(2, 5)], function ($x, $y) {
            return $x->add($y);
        })->run($this->conn);
        $this->assertEquals([3, 5, 7], (array) $res);
    }

    public function tesRangetMapMultipleFunc()
    {
        $res = range(1, 4)->mapMultiple(range(2, 5), function ($x, $y) {
            return $x->add($y);
        })->run($this->conn);
        $this->assertEquals([3, 5, 7], $res->toArray());
    }

    public function testMapMultipleRangeAddSub()
    {
        $res = range(1, 4)->mapMultiple([range(2, 5), range(1, 4)], function ($x, $y, $z) {
            return $x->add($y)->sub($z);
        })->run($this->conn);
        $this->assertEquals([2, 3, 4], $res->toArray());
    }
}
