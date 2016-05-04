<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

// use function \r\eq;
// use function \r\le;
// use function \r\ne;
// use function \r\gt;
// use function \r\ge;
// use function \r\lt;
// use function \r\add;
// use function \r\sub;
// use function \r\mul;
// use function \r\div;
// use function \r\mod;
// use function \r\not;
// use function \r\rOr;
// use function \r\rAnd;
// use function \r\expr;
// use function \r\ceil;
// use function \r\floor;
// use function \r\round;
// use function \r\random;

class MathTest extends TestCase
{
    public function testMatchNull()
    {
        $this->assertNull(\r\expr('a')->match('b')->run($this->conn));
    }

    public function testMatch()
    {
        $this->assertEquals(
            array('str' => 'b', 'start' => 0, 'groups' => array(), 'end' => 1),
            (array)\r\expr('b')->match('b')->run($this->conn)
        );
    }

    public function testMatchRegex()
    {
        $res = \r\expr('id:0,name:mlucy,foo:bar')->match('name:(\w+)')->run($this->conn);

        $res = (array)$res;
        $res['groups'][0] = (array)$res['groups'][0];

        $this->assertEquals(
            array('str' => 'name:mlucy', 'start' => 5, 'groups' => array(
                array('str' => 'mlucy', 'start' => 10, 'end' => 15)
            ), 'end' => 15),
            $res
        );
    }

    public function testUppercase()
    {
        $this->assertEquals('AA', \r\expr('aA')->upcase()->run($this->conn));
    }

    public function testLowercase()
    {
        $this->assertEquals('aa', \r\expr('aA')->downcase()->run($this->conn));
    }

    public function testSplitNoArgs()
    {
        $this->assertEquals(
            array('foo', 'bar', 'bax'),
            \r\expr('foo bar bax')->split()->run($this->conn)
        );
    }

    public function testSplitCom()
    {
        $this->assertEquals(
            array('foo', 'bar', 'bax'),
            \r\expr('foo,bar,bax')->split(',')->run($this->conn)
        );
    }

    public function testSplitString()
    {
        $this->assertEquals(
            array('f', 'o', 'o'),
            \r\expr('foo')->split('')->run($this->conn)
        );
    }

    public function testSplitGroups()
    {
        $this->assertEquals(
            array('foo', 'bar bax'),
            \r\expr('foo bar bax')->split(null, 1)->run($this->conn)
        );
    }

    public function testAddText()
    {
        $this->assertEquals('ab', \r\expr('a')->add('b')->run($this->conn));
    }

    public function testAddition()
    {
        $this->assertEquals(3.0, \r\expr(1)->add(2)->run($this->conn));
    }

    public function testSubtraction()
    {
        $this->assertEquals(-1.0, \r\expr(1)->sub(2)->run($this->conn));
    }

    public function testMultiplication()
    {
        $this->assertEquals(2.0, \r\expr(1)->mul(2)->run($this->conn));
    }

    public function testDivision()
    {
        $this->assertEquals(0.5, \r\expr(1)->div(2)->run($this->conn));
    }

    public function testModulo()
    {
        $this->assertEquals(1.0, \r\expr(1)->mod(2)->run($this->conn));
    }

    public function testAndTrueTrue()
    {
        $this->assertTrue(\r\expr(true)->rAnd(true)->run($this->conn));
    }

    public function testAndTrueFalse()
    {
        $this->assertFalse(\r\expr(true)->rAnd(false)->run($this->conn));
    }

    public function testAndFalseTrue()
    {
        $this->assertFalse(\r\expr(false)->rAnd(true)->run($this->conn));
    }

    public function testAndFalseFalse()
    {
        $this->assertFalse(\r\expr(false)->rAnd(false)->run($this->conn));
    }

    public function testOrTrueTrue()
    {
        $this->assertTrue(\r\expr(true)->rOr(true)->run($this->conn));
    }

    public function testOrTrueFalse()
    {
        $this->assertTrue(\r\expr(true)->rOr(false)->run($this->conn));
    }

    public function testOrFalseTrue()
    {
        $this->assertTrue(\r\expr(false)->rOr(true)->run($this->conn));
    }

    public function testOrFalseFalse()
    {
        $this->assertFalse(\r\expr(false)->rOr(false)->run($this->conn));
    }

    public function testEq()
    {
        $this->assertTrue(\r\expr(1.0)->eq(1.0)->run($this->conn));
    }

    public function testEqNeg()
    {
        $this->assertFalse(\r\expr(1.0)->eq(-1.0)->run($this->conn));
    }

    public function testNe()
    {
        $this->assertFalse(\r\expr(1.0)->ne(1.0)->run($this->conn));
    }

    public function testNeNeg()
    {
        $this->assertTrue(\r\expr(1.0)->ne(-1.0)->run($this->conn));
    }

    public function testGt()
    {
        $this->assertFalse(\r\expr(1.0)->ne(1.0)->run($this->conn));
    }

    public function testGtNeg()
    {
        $this->assertTrue(\r\expr(1.0)->ne(-1.0)->run($this->conn));
    }

    public function testGe()
    {
        $this->assertTrue(\r\expr(1.0)->ge(1.0)->run($this->conn));
    }

    public function testGeNeg()
    {
        $this->assertTrue(\r\expr(1.0)->ge(-1.0)->run($this->conn));
    }

    public function testLt()
    {
        $this->assertFalse(\r\expr(1.0)->lt(1.0)->run($this->conn));
    }

    public function testLtNeg()
    {
        $this->assertFalse(\r\expr(1.0)->lt(-1.0)->run($this->conn));
    }

    public function testLe()
    {
        $this->assertTrue(\r\expr(1.0)->le(1.0)->run($this->conn));
    }

    public function testLeNeg()
    {
        $this->assertFalse(\r\expr(1.0)->le(-1.0)->run($this->conn));
    }

    public function testNotTrue()
    {
        $this->assertFalse(\r\expr(true)->not()->run($this->conn));
    }

    public function testNotFalse()
    {
        $this->assertTrue(\r\expr(false)->not()->run($this->conn));
    }

    public function testAdditionExpression()
    {
        $this->assertEquals(5.0, \r\add(\r\expr(3), \r\expr(2))->run($this->conn));
    }

    public function testSubtractionExpression()
    {
        $this->assertEquals(1.0, \r\sub(\r\expr(3), \r\expr(2))->run($this->conn));
    }

    public function testMultiplicationExpression()
    {
        $this->assertEquals(6.0, \r\mul(\r\expr(3), \r\expr(2))->run($this->conn));
    }

    public function testDivisionExpression()
    {
        $this->assertEquals(3.5, \r\div(\r\expr(7), \r\expr(2))->run($this->conn));
    }

    public function testModuloExpression()
    {
        $this->assertEquals(1.0, \r\mod(\r\expr(7), \r\expr(2))->run($this->conn));
    }

    public function testAndExpressionTrueTrue()
    {
        $this->assertTrue(\r\rAnd(\r\expr(true), \r\expr(true))->run($this->conn));
    }

    public function testAndExpressionTrueFalse()
    {
        $this->assertFalse(\r\rAnd(\r\expr(true), \r\expr(false))->run($this->conn));
    }

    public function testAndExpressionFalseTrue()
    {
        $this->assertFalse(\r\rAnd(\r\expr(false), \r\expr(true))->run($this->conn));
    }

    public function testAndExpressionFalseFalse()
    {
        $this->assertFalse(\r\rAnd(\r\expr(false), \r\expr(false))->run($this->conn));
    }

    public function testOrExpressionTrueTrue()
    {
        $this->assertTrue(\r\rOr(\r\expr(true), \r\expr(true))->run($this->conn));
    }

    public function testOrExpressionTrueFalse()
    {
        $this->assertTrue(\r\rOr(\r\expr(true), \r\expr(false))->run($this->conn));
    }

    public function testOrExpressionFalseTrue()
    {
        $this->assertTrue(\r\rOr(\r\expr(false), \r\expr(true))->run($this->conn));
    }

    public function testOrExpressionFalseFalse()
    {
        $this->assertFalse(\r\rOr(\r\expr(false), \r\expr(false))->run($this->conn));
    }

    public function testEqExpressionFalse()
    {
        $this->assertFalse(\r\eq(\r\expr(5), \r\expr(6))->run($this->conn));
    }

    public function testEqExpressionTrue()
    {
        $this->assertTrue(\r\eq(\r\expr(6), \r\expr(6))->run($this->conn));
    }

    public function testNeExpressionTrue()
    {
        $this->assertTrue(\r\ne(\r\expr(5), \r\expr(6))->run($this->conn));
    }

    public function testNeExpressionFalse()
    {
        $this->assertFalse(\r\ne(\r\expr(6), \r\expr(6))->run($this->conn));
    }

    public function testGtExpressionFalse()
    {
        $this->assertFalse(\r\gt(\r\expr(5), \r\expr(6))->run($this->conn));
    }

    public function testGtExpressionFalseEq()
    {
        $this->assertFalse(\r\gt(\r\expr(6), \r\expr(6))->run($this->conn));
    }

    public function testGtExpressionTrue()
    {
        $this->assertTrue(\r\gt(\r\expr(6), \r\expr(5))->run($this->conn));
    }

    public function testGeExpressionFalse()
    {
        $this->assertFalse(\r\ge(\r\expr(5), \r\expr(6))->run($this->conn));
    }

    public function testGeExpressionTrueEq()
    {
        $this->assertTrue(\r\ge(\r\expr(6), \r\expr(6))->run($this->conn));
    }

    public function testGeExpressionTrue()
    {
        $this->assertTrue(\r\ge(\r\expr(6), \r\expr(5))->run($this->conn));
    }

    public function testLtExpressionTrue()
    {
        $this->assertTrue(\r\lt(\r\expr(5), \r\expr(6))->run($this->conn));
    }

    public function testLtExpressionFalseEq()
    {
        $this->assertFalse(\r\lt(\r\expr(6), \r\expr(6))->run($this->conn));
    }

    public function testLtExpressionFalse()
    {
        $this->assertFalse(\r\lt(\r\expr(6), \r\expr(5))->run($this->conn));
    }

    public function testLeExpressionTrue()
    {
        $this->assertTrue(\r\le(\r\expr(5), \r\expr(6))->run($this->conn));
    }

    public function testLeExpressionTrueEq()
    {
        $this->assertTrue(\r\le(\r\expr(6), \r\expr(6))->run($this->conn));
    }

    public function testLeExpressionFalse()
    {
        $this->assertFalse(\r\le(\r\expr(6), \r\expr(5))->run($this->conn));
    }

    public function testNotExpressionFalse()
    {
        $this->assertFalse(\r\not(\r\expr(true))->run($this->conn));
    }

    public function testNotExpressionTrue()
    {
        $this->assertTrue(\r\not(\r\expr(false))->run($this->conn));
    }

    public function testRandom()
    {
        for ($i = 0; $i < 10; ++$i) {
            $this->assertTrue(\r\random()->lt(1.0)->run($this->conn));
            $this->assertTrue(\r\random()->ge(0.0)->run($this->conn));
        }
    }

    public function testRandomMax()
    {
        for ($i = 0; $i < 10; ++$i) {
            $this->assertTrue(\r\random(10)->lt(10)->run($this->conn));
            $this->assertTrue(\r\random(10)->ge(0.0)->run($this->conn));
        }
    }

    public function testRandomRange()
    {
        for ($i = 0; $i < 10; ++$i) {
            $this->assertTrue(\r\random(5, 10)->lt(10)->run($this->conn));
            $this->assertTrue(\r\random(5, 10)->ge(5.0)->run($this->conn));
        }
    }

    public function testCeil()
    {
        $this->assertEquals(2.0, \r\ceil(1.5)->run($this->conn));
    }

    public function testFloor()
    {
        $this->assertEquals(1.0, \r\floor(1.5)->run($this->conn));
    }

    public function testRound()
    {
        $this->assertEquals(1.0, \r\round(1.4)->run($this->conn));
        $this->assertEquals(2.0, \r\round(1.5)->run($this->conn));
    }

    public function testExprCeil()
    {
        $this->assertEquals(2.0, \r\expr(1.5)->ceil()->run($this->conn));
    }

    public function testExprFloor()
    {
        $this->assertEquals(1.0, \r\expr(1.5)->floor()->run($this->conn));
    }
    
    public function testExprRound()
    {
        $this->assertEquals(2.0, \r\expr(1.5)->round()->run($this->conn));
    }
}
