<?php

namespace r\Tests\Functional;

use function \r\row;
use function \r\Desc;
use function \r\Asc;
use function \r\expr;
use r\Datum\ArrayDatum;
use r\Tests\TestCase;
use function r\union;

class TransformationsTest extends TestCase
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

    public function testOrderbyMap()
    {
        $res = $this->db()->table('marvel')->orderBy('combatPower', 'compassionPower')->map(row('superhero'))->run($this->conn);
        $this->assertEquals(['Iron Man', 'Spiderman', 'Wolverine'], $res);
    }

    public function testOrderbyMapDesc()
    {
        $res = $this->db()->table('marvel')->orderBy(Desc('combatPower'), Desc('compassionPower'))->map(row('superhero'))->run($this->conn);
        $this->assertEquals(['Wolverine', 'Spiderman', 'Iron Man'], $res);
    }

    public function testOrderbyMapAsc()
    {
        $res = $this->db()->table('marvel')->orderBy(Asc('combatPower'), Asc('compassionPower'))->map(row('superhero'))->run($this->conn);
        $this->assertEquals(['Iron Man', 'Spiderman', 'Wolverine'], $res);
    }

    public function testOrderbyMapRow()
    {
        $res = $this->db()->table('marvel')->orderBy(Asc('combatPower'), Asc('compassionPower'))->map(row('superhero'))->run($this->conn);
        $this->assertEquals(['Iron Man', 'Spiderman', 'Wolverine'], $res);
    }

    public function testOrderbyMapAscDesc()
    {
        $res = $this->db()->table('marvel')->orderBy(Asc(row('combatPower')), Desc(row('compassionPower')))->map(row('superhero'))->run($this->conn);
        $this->assertEquals(['Spiderman', 'Iron Man', 'Wolverine'], $res);
    }

    public function testOrderbyCallback()
    {
        $res = $this->db()->table('marvel')->orderBy(function ($x) {
            return $x('combatPower');
        }, function ($x) {
            return $x('compassionPower');
        })->map(row('superhero'))->run($this->conn);
        $this->assertEquals(['Iron Man', 'Spiderman', 'Wolverine'], $res);
    }

    public function testOrderbyAscDescCallback()
    {
        $res = $this->db()->table('marvel')->orderBy(Asc(function ($x) {
            return $x('combatPower');
        }), Desc(function ($x) {
            return $x('compassionPower');
        }))->map(row('superhero'))->run($this->conn);
        $this->assertEquals(['Spiderman', 'Iron Man', 'Wolverine'], $res);
    }

    public function testOrderbySkip()
    {
        $res = $this->db()->table('marvel')->orderBy('superhero')->skip(1)->map(row('superhero'))->run($this->conn);
        $this->assertEquals(['Spiderman', 'Wolverine'], $res);
    }

    public function testOrderbyLimit()
    {
        $res = $this->db()->table('marvel')->orderBy('superhero')->limit(1)->map(row('superhero'))->run($this->conn);
        $this->assertEquals(['Iron Man'], $res);
    }

    public function testOrderbyNthPos()
    {
        $res = $this->db()->table('marvel')->orderBy('superhero')->nth(1)->getField('superhero')->run($this->conn);
        $this->assertEquals('Spiderman', $res);
    }

    public function testOrderbyNthNeg()
    {
        $res = $this->db()->table('marvel')->orderBy('superhero')->nth(-1)->getField('superhero')->run($this->conn);
        $this->assertEquals('Wolverine', $res);
    }

    public function testOrderbySlice()
    {
        $res = $this->db()->table('marvel')->orderBy('superhero')->slice(1)->map(row('superhero'))->run($this->conn);
        $this->assertEquals(['Spiderman', 'Wolverine'], $res);
    }

    public function testOrderbySliceFromFirst()
    {
        $res = $this->db()->table('marvel')->orderBy('superhero')->slice(1, 1)->map(row('superhero'))->run($this->conn);
        $this->assertEquals([], $res);
    }

    public function testOrderbySliceFromToSecond()
    {
        $res = $this->db()->table('marvel')->orderBy('superhero')->slice(1, 2)->map(row('superhero'))->run($this->conn);
        $this->assertEquals(['Spiderman'], $res);
    }

    public function testOrderbySliceFromToRightBound()
    {
        $res = $this->db()->table('marvel')->orderBy('superhero')->slice(1, 1, ['right_bound' => 'closed'])->map(row('superhero'))->run($this->conn);
        $this->assertEquals(['Spiderman'], $res);
    }

    public function testPluckUnion()
    {
        $res = $this->db()->table('marvel')->pluck('superhero')->union(expr([['superhero' => 'foo']]))->map(row('superhero'))->run($this->conn);
        $this->assertInstanceOf('\r\Cursor', $res);
        $this->assertEquals(['foo', 'Spiderman', 'Wolverine', 'Iron Man'], $res->toArray());
    }

    public function testGlobalUnion()
    {
        $this->assertEquals([1, 3, 2, 4], union(expr([1, 3]), expr([2, 4]), ['interleave' => false])->run($this->conn));
    }

    public function testGlobalUnionInterleave()
    {
        $this->assertEquals([1, 2, 3, 4], union(expr([['a' => 1], ['a' => 3]]), expr([['a' => 2], ['a' => 4]]), ['interleave' => 'a'])->getField('a')->run($this->conn));
    }

    public function testWithFields()
    {
        $res = $this->db()->table('marvel')->withFields('superhero', 'nemesis')->count()->run($this->conn);
        $this->assertEquals(0.0, $res);
    }

    public function testWithFieldsMultiple()
    {
        $res = $this->db()->table('marvel')->withFields('superhero')->count()->run($this->conn);
        $this->assertEquals(3.0, $res);
    }

    public function testWithFieldsTrue()
    {
        $res = $this->db()->table('marvel')->withFields(['superhero' => true])->count()->run($this->conn);
        $this->assertEquals(3.0, $res);
    }

    public function testOffsetOf()
    {
        $res = expr(['a', 'b', 'c'])->offsetsOf('c')->run($this->conn);
        $this->assertEquals([2], $res);
    }

    public function testIsEmpty()
    {
        $res = $this->db()->table('marvel')->isEmpty()->run($this->conn);
        $this->assertEquals(false, $res);
    }

    public function testIsEmptyArrayDatum()
    {
        $res = expr(new ArrayDatum([]))->isEmpty()->run($this->conn);
        $this->assertEquals(true, $res);
    }

    public function testIsSample()
    {
        $res = $this->db()->table('marvel')->sample(1)->count()->run($this->conn);
        $this->assertEquals(1.0, $res);
    }

    public function testIsSampleMultiple()
    {
        $res = $this->db()->table('marvel')->sample(3)->count()->run($this->conn);
        $this->assertEquals(3.0, $res);
    }
}
