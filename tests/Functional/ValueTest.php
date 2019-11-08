<?php

namespace r\Tests\Functional;

use r\Datum\ArrayDatum;
use r\Datum\ObjectDatum;
use function r\expr;
use r\Datum\StringDatum;
use r\Tests\TestCase;

class ValueTest extends TestCase
{
    public function testNull()
    {
        $this->assertNull(expr(null)->run($this->conn));
    }

    public function testTrue()
    {
        $this->assertTrue(expr(true)->run($this->conn));
    }

    public function testFalse()
    {
        $this->assertFalse(expr(false)->run($this->conn));
    }

    public function testFloat()
    {
        $this->assertEquals(0.5, expr(0.5)->run($this->conn));
    }

    public function testZero()
    {
        $this->assertEquals(0, expr(0)->run($this->conn));
    }

    public function testNegative()
    {
        $this->assertEquals(-1, expr(-1)->run($this->conn));
    }

    public function testPositive()
    {
        $this->assertEquals(1, expr(1)->run($this->conn));
    }

    public function testFloatMax()
    {
        $this->assertLessThan(10, (float) PHP_INT_MAX - expr(PHP_INT_MAX)->run($this->conn));
    }

    public function testString()
    {
        $this->assertEquals('foo', expr('foo')->run($this->conn));
    }

    public function testArrayString()
    {
        $this->assertEquals(['foo' => 'val'], (array) expr(['foo' => 'val'])->run($this->conn));
    }

    public function testArrayFloat()
    {
        $this->assertEquals(['foo' => 7.0], (array) expr(['foo' => 7])->run($this->conn));
    }

    public function testArrayNull()
    {
        $this->assertEquals(['foo' => null], (array) expr(['foo' => null])->run($this->conn));
    }

    public function testArrayTrue()
    {
        $this->assertEquals(['foo' => true], (array) (array) expr(['foo' => true])->run($this->conn));
    }

    public function testArrayMultipleNum()
    {
        $this->assertEquals([1.0, 2.0, 3.0], (array) expr([1, 2, 3])->run($this->conn));
    }

    public function testArrayMultipleMixed()
    {
        $this->assertEquals([1.0, 'foo', true, null], (array) expr([1.0, 'foo', true, null])->run($this->conn));
    }

    public function testArrayDatum()
    {
        $ob = new ArrayDatum([]);
        $this->assertEquals([], $ob->run($this->conn));
    }

    public function testObjectDatumArray()
    {
        $ob = new ArrayDatum([]);
        $this->assertEquals([], $ob->run($this->conn));
    }

    public function testObjectDatumNum()
    {
        $ob = new ObjectDatum([4 => new StringDatum('a')]);
        $this->assertEquals([4 => 'a'], (array) $ob->run($this->conn));
    }

    public function testObjectDatumString()
    {
        $ob = new ObjectDatum([4 => new StringDatum('a')]);
        $this->assertEquals(['4' => 'a'], (array) $ob->run($this->conn));
    }

    public function testToObject()
    {
        $this->assertEquals([], (array) expr((object) [])->run($this->conn));
    }

    public function testObjectDatum()
    {
        $res = expr([new ObjectDatum([])])->run($this->conn);
        $this->assertEquals([[]], $this->toArray($res));
    }

    public function testObjectAsArray()
    {
        $res = expr([(object) []])->run($this->conn);
        $this->assertEquals([[]], $this->toArray($res));
    }
}
