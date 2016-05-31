<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;
use r\Datum\ArrayDatum;

// use function \r\row;
// use function \r\Desc;
// use function \r\Asc;
// use function \r\expr;

class TransformationsTest extends TestCase
{
    public function setUp()
    {
        $this->conn = $this->getConnection();
        $this->data = $this->useDataset('Heroes');
        $this->data->populate();
    }

    public function tearDown()
    {
        $this->data->truncate();
    }

    public function testOrderbyMap()
    {
        $res = $this->db()->table('marvel')->orderBy(
            array('combatPower', 'compassionPower')
        )->map(
            \r\row('superhero')
        )->run($this->conn);

        $this->assertEquals(array('Iron Man', 'Spiderman', 'Wolverine'), $res);
    }

    public function testOrderbyMapDesc()
    {
        $res = $this->db()->table('marvel')->orderBy(
            array(\r\Desc('combatPower'), \r\Desc('compassionPower'))
        )->map(
            \r\row('superhero')
        )->run($this->conn);

        $this->assertEquals(array('Wolverine', 'Spiderman', 'Iron Man'), $res);
    }

    public function testOrderbyMapAsc()
    {
        $res = $this->db()->table('marvel')->orderBy(
            array(\r\Asc('combatPower'), \r\Asc('compassionPower'))
        )->map(
            \r\row('superhero')
        )->run($this->conn);

        $this->assertEquals(array('Iron Man', 'Spiderman', 'Wolverine'), $res);
    }

    public function testOrderbyMapRow()
    {
        $res = $this->db()->table('marvel')->orderBy(
            array(\r\Asc('combatPower'), \r\Asc('compassionPower'))
        )->map(
            \r\row('superhero')
        )->run($this->conn);

        $this->assertEquals(array('Iron Man', 'Spiderman', 'Wolverine'), $res);
    }

    public function testOrderbyMapAscDesc()
    {
        $res = $this->db()->table('marvel')->orderBy(
            array(\r\Asc(\r\row('combatPower')), \r\Desc(\r\row('compassionPower')))
        )->map(
            \r\row('superhero')
        )->run($this->conn);

        $this->assertEquals(array('Spiderman', 'Iron Man', 'Wolverine'), $res);
    }

    public function testOrderbyCallback()
    {
        $res = $this->db()->table('marvel')->orderBy(
            array(function ($x) {
                return $x('combatPower');

            }, function ($x) {
                return $x('compassionPower');
            })
        )->map(
            \r\row('superhero')
        )->run($this->conn);

        $this->assertEquals(array('Iron Man', 'Spiderman', 'Wolverine'), $res);
    }

    public function testOrderbyAscDescCallback()
    {
        $res = $this->db()->table('marvel')->orderBy(
            array(\r\Asc(function ($x) {
                return $x('combatPower');

            }), \r\Desc(function ($x) {
                return $x('compassionPower');
            }))
        )->map(
            \r\row('superhero')
        )->run($this->conn);

        $this->assertEquals(array('Spiderman', 'Iron Man', 'Wolverine'), $res);
    }

    public function testOrderbySkip()
    {
        $res = $this->db()->table('marvel')
            ->orderBy('superhero')
            ->skip(1)
            ->map(\r\row('superhero'))
            ->run($this->conn);

        $this->assertEquals(array('Spiderman', 'Wolverine'), $res);
    }

    public function testOrderbyLimit()
    {
        $res = $this->db()->table('marvel')
            ->orderBy('superhero')
            ->limit(1)
            ->map(\r\row('superhero'))
            ->run($this->conn);

        $this->assertEquals(array('Iron Man'), $res);
    }

    public function testOrderbyNthPos()
    {
        $res = $this->db()->table('marvel')
            ->orderBy('superhero')
            ->nth(1)
            ->getField('superhero')
            ->run($this->conn);

        $this->assertEquals('Spiderman', $res);
    }

    public function testOrderbyNthNeg()
    {
        $res = $this->db()->table('marvel')
            ->orderBy('superhero')
            ->nth(-1)
            ->getField('superhero')
            ->run($this->conn);

        $this->assertEquals('Wolverine', $res);
    }

    public function testOrderbySlice()
    {
        $res = $this->db()->table('marvel')
            ->orderBy('superhero')
            ->slice(1)
            ->map(\r\row('superhero'))
            ->run($this->conn);

        $this->assertEquals(array('Spiderman', 'Wolverine'), $res);
    }

    public function testOrderbySliceFromFirst()
    {
        $res = $this->db()->table('marvel')
            ->orderBy('superhero')
            ->slice(1, 1)
            ->map(\r\row('superhero'))
            ->run($this->conn);

        $this->assertEquals(array(), $res);
    }

    public function testOrderbySliceFromToSecond()
    {
        $res = $this->db()->table('marvel')
            ->orderBy('superhero')
            ->slice(1, 2)
            ->map(\r\row('superhero'))
            ->run($this->conn);

        $this->assertEquals(array('Spiderman'), $res);
    }

    public function testOrderbySliceFromToRightBound()
    {
        $res = $this->db()->table('marvel')
            ->orderBy('superhero')
            ->slice(1, 1, array('right_bound' => 'closed'))
            ->map(\r\row('superhero'))
            ->run($this->conn);

        $this->assertEquals(array('Spiderman'), $res);
    }

    public function testPluckUnion()
    {
        $res = $this->db()->table('marvel')
            ->pluck('superhero')
            ->union(\r\expr(array(array('superhero' => 'foo'))))
            ->map(\r\row('superhero'))
            ->run($this->conn);

        $this->assertInstanceOf('\r\Cursor', $res);
        $this->assertEquals(
            array('foo', 'Spiderman', 'Wolverine', 'Iron Man'),
            $res->toArray()
        );
    }

    public function testGlobalUnion()
    {
        $this->assertEquals(
            array(1, 3, 2, 4),
            \r\union(\r\expr(array(1, 3)), \r\expr(array(2, 4)), array('interleave' => false))
            ->run($this->conn)
        );
    }

    public function testGlobalUnionInterleave()
    {
        $this->assertEquals(
            array(1, 2, 3, 4),
            \r\union(\r\expr(array(array('a' => 1), array('a' => 3))),
                \r\expr(array(array('a' => 2), array('a' => 4))),
                array('interleave' => 'a'))
            ->getField('a')
            ->run($this->conn)
        );
    }

    public function testWithFields()
    {
        $res = $this->db()->table('marvel')
            ->withFields(array('superhero', 'nemesis'))
            ->count()
            ->run($this->conn);

        $this->assertEquals(0.0, $res);
    }

    public function testWithFieldsMultiple()
    {
        $res = $this->db()->table('marvel')
            ->withFields(array('superhero'))
            ->count()
            ->run($this->conn);

        $this->assertEquals(3.0, $res);
    }

    public function testWithFieldsTrue()
    {
        $res = $this->db()->table('marvel')
            ->withFields(array('superhero' => true))
            ->count()
            ->run($this->conn);

        $this->assertEquals(3.0, $res);
    }

    public function testOffsetOf()
    {
        $res = \r\expr(array('a','b','c'))->offsetsOf('c')->run($this->conn);

        $this->assertEquals(array(2), $res);
    }

    public function testIsEmpty()
    {
        $res = $this->db()->table('marvel')->isEmpty()->run($this->conn);

        $this->assertEquals(false, $res);
    }

    public function testIsEmptyArrayDatum()
    {
        $res = \r\expr(new ArrayDatum(array()))->isEmpty()->run($this->conn);

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
