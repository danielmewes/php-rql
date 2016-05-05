<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;
use r\Datum\ArrayDatum;
use r\Datum\ObjectDatum;
use r\Datum\StringDatum;

// use function \r\expr;

class ValueTest extends TestCase
{
    public function testNull()
    {
        $this->assertNull(\r\expr(null)->run($this->conn));
    }

    public function testTrue()
    {
        $this->assertTrue(\r\expr(true)->run($this->conn));
    }

    public function testFalse()
    {
        $this->assertFalse(\r\expr(false)->run($this->conn));
    }

    public function testFloat()
    {
        $this->assertEquals(0.5, \r\expr(0.5)->run($this->conn));
    }

    public function testZero()
    {
        $this->assertEquals(0, \r\expr(0)->run($this->conn));
    }

    public function testNegative()
    {
        $this->assertEquals(-1, \r\expr(-1)->run($this->conn));
    }

    public function testPositive()
    {
        $this->assertEquals(1, \r\expr(1)->run($this->conn));
    }

    public function testFloatMax()
    {
        $this->assertLessThan(10, (float)PHP_INT_MAX - \r\expr(PHP_INT_MAX)->run($this->conn));
    }

    public function testString()
    {
        $this->assertEquals('foo', \r\expr('foo')->run($this->conn));
    }

    public function testArrayString()
    {
        $this->assertEquals(
            array('foo' => 'val'),
            (array)\r\expr(array('foo' => 'val'))->run($this->conn)
        );
    }

    public function testArrayFloat()
    {
        $this->assertEquals(
            array('foo' => 7.0),
            (array)\r\expr(array('foo' => 7))->run($this->conn)
        );
    }

    public function testArrayNull()
    {
        $this->assertEquals(
            array('foo' => null),
            (array)\r\expr(array('foo' => null))->run($this->conn)
        );
    }

    public function testArrayTrue()
    {
        $this->assertEquals(
            array('foo' => true),
            (array)(array)\r\expr(array('foo' => true))->run($this->conn)
        );
    }

    public function testArrayMultipleNum()
    {
        $this->assertEquals(
            array(1.0, 2.0, 3.0),
            (array)\r\expr(array(1, 2, 3))->run($this->conn)
        );
    }

    public function testArrayMultipleMixed()
    {
        $this->assertEquals(
            array(1.0, 'foo', true, null),
            (array)\r\expr(array(1.0, 'foo', true, null))->run($this->conn)
        );
    }

    public function testArrayDatum()
    {
        $ob = new ArrayDatum(array());
        $this->assertEquals(array(), $ob->run($this->conn));
    }

    public function testObjectDatumArray()
    {
        $ob = new ArrayDatum(array());
        $this->assertEquals(array(), $ob->run($this->conn));
    }

    public function testObjectDatumNum()
    {
        $ob = new ObjectDatum(array(4 => new StringDatum('a')));
        $this->assertEquals(array(4 => 'a'), (array)$ob->run($this->conn));
    }

    public function testObjectDatumString()
    {
        $ob = new ObjectDatum(array(4 => new StringDatum('a')));
        $this->assertEquals(array('4' => 'a'), (array)$ob->run($this->conn));
    }

    public function testToObject()
    {
        $this->assertEquals(
            array(),
            (array)\r\expr((Object)array())->run($this->conn)
        );
    }

    public function testObjectDatum()
    {
        $res = \r\expr(array(new ObjectDatum(array())))->run($this->conn);

        $this->assertEquals(array(array()), $this->toArray($res));
    }

    public function testObjectAsArray()
    {
        $res = \r\expr(array((Object)array()))->run($this->conn);

        $this->assertEquals(array(array()), $this->toArray($res));
    }
}
