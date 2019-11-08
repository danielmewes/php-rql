<?php

namespace r\Tests\Functional;

use function \r\eq;
use function \r\le;
use function \r\ne;
use function \r\gt;
use function \r\ge;
use function \r\lt;
use function \r\add;
use function \r\sub;
use function \r\mul;
use function \r\div;
use function \r\mod;
use function \r\not;
use function \r\rOr;
use function \r\rAnd;
use function \r\expr;
use function \r\ceil;
use function \r\floor;
use function \r\round;
use function \r\random;
use r\Tests\TestCase;

class MathTest extends TestCase
{
    public function testMatchNull()
    {
        $this->assertNull(expr('a')->match('b')->run($this->conn));
    }

    public function testMatch()
    {
        $this->assertEquals(['str' => 'b', 'start' => 0, 'groups' => [], 'end' => 1], (array) expr('b')->match('b')->run($this->conn));
    }

    public function testMatchRegex()
    {
        $res = expr('id:0,name:mlucy,foo:bar')->match('name:(\w+)')->run($this->conn);
        $res = (array) $res;
        $res['groups'][0] = (array) $res['groups'][0];
        $this->assertEquals(['str' => 'name:mlucy', 'start' => 5, 'groups' => [['str' => 'mlucy', 'start' => 10, 'end' => 15]], 'end' => 15], $res);
    }

    public function testUppercase()
    {
        $this->assertEquals('AA', expr('aA')->upcase()->run($this->conn));
    }

    public function testLowercase()
    {
        $this->assertEquals('aa', expr('aA')->downcase()->run($this->conn));
    }

    public function testSplitNoArgs()
    {
        $this->assertEquals(['foo', 'bar', 'bax'], expr('foo bar bax')->split()->run($this->conn));
    }

    public function testSplitCom()
    {
        $this->assertEquals(['foo', 'bar', 'bax'], expr('foo,bar,bax')->split(',')->run($this->conn));
    }

    public function testSplitString()
    {
        $this->assertEquals(['f', 'o', 'o'], expr('foo')->split('')->run($this->conn));
    }

    public function testSplitGroups()
    {
        $this->assertEquals(['foo', 'bar bax'], expr('foo bar bax')->split(null, 1)->run($this->conn));
    }

    public function testAddText()
    {
        $this->assertEquals('ab', expr('a')->add('b')->run($this->conn));
    }

    public function testAddition()
    {
        $this->assertEquals(3.0, expr(1)->add(2)->run($this->conn));
    }

    public function testSubtraction()
    {
        $this->assertEquals(-1.0, expr(1)->sub(2)->run($this->conn));
    }

    public function testMultiplication()
    {
        $this->assertEquals(2.0, expr(1)->mul(2)->run($this->conn));
    }

    public function testDivision()
    {
        $this->assertEquals(0.5, expr(1)->div(2)->run($this->conn));
    }

    public function testModulo()
    {
        $this->assertEquals(1.0, expr(1)->mod(2)->run($this->conn));
    }

    public function testAndTrueTrue()
    {
        $this->assertTrue(expr(true)->rAnd(true)->run($this->conn));
    }

    public function testAndTrueFalse()
    {
        $this->assertFalse(expr(true)->rAnd(false)->run($this->conn));
    }

    public function testAndFalseTrue()
    {
        $this->assertFalse(expr(false)->rAnd(true)->run($this->conn));
    }

    public function testAndFalseFalse()
    {
        $this->assertFalse(expr(false)->rAnd(false)->run($this->conn));
    }

    public function testOrTrueTrue()
    {
        $this->assertTrue(expr(true)->rOr(true)->run($this->conn));
    }

    public function testOrTrueFalse()
    {
        $this->assertTrue(expr(true)->rOr(false)->run($this->conn));
    }

    public function testOrFalseTrue()
    {
        $this->assertTrue(expr(false)->rOr(true)->run($this->conn));
    }

    public function testOrFalseFalse()
    {
        $this->assertFalse(expr(false)->rOr(false)->run($this->conn));
    }

    public function testEq()
    {
        $this->assertTrue(expr(1.0)->eq(1.0)->run($this->conn));
    }

    public function testEqNeg()
    {
        $this->assertFalse(expr(1.0)->eq(-1.0)->run($this->conn));
    }

    public function testNe()
    {
        $this->assertFalse(expr(1.0)->ne(1.0)->run($this->conn));
    }

    public function testNeNeg()
    {
        $this->assertTrue(expr(1.0)->ne(-1.0)->run($this->conn));
    }

    public function testGt()
    {
        $this->assertFalse(expr(1.0)->ne(1.0)->run($this->conn));
    }

    public function testGtNeg()
    {
        $this->assertTrue(expr(1.0)->ne(-1.0)->run($this->conn));
    }

    public function testGe()
    {
        $this->assertTrue(expr(1.0)->ge(1.0)->run($this->conn));
    }

    public function testGeNeg()
    {
        $this->assertTrue(expr(1.0)->ge(-1.0)->run($this->conn));
    }

    public function testLt()
    {
        $this->assertFalse(expr(1.0)->lt(1.0)->run($this->conn));
    }

    public function testLtNeg()
    {
        $this->assertFalse(expr(1.0)->lt(-1.0)->run($this->conn));
    }

    public function testLe()
    {
        $this->assertTrue(expr(1.0)->le(1.0)->run($this->conn));
    }

    public function testLeNeg()
    {
        $this->assertFalse(expr(1.0)->le(-1.0)->run($this->conn));
    }

    public function testNotTrue()
    {
        $this->assertFalse(expr(true)->not()->run($this->conn));
    }

    public function testNotFalse()
    {
        $this->assertTrue(expr(false)->not()->run($this->conn));
    }

    public function testAdditionExpression()
    {
        $this->assertEquals(5.0, add(expr(3), expr(2))->run($this->conn));
    }

    public function testSubtractionExpression()
    {
        $this->assertEquals(1.0, sub(expr(3), expr(2))->run($this->conn));
    }

    public function testMultiplicationExpression()
    {
        $this->assertEquals(6.0, mul(expr(3), expr(2))->run($this->conn));
    }

    public function testDivisionExpression()
    {
        $this->assertEquals(3.5, div(expr(7), expr(2))->run($this->conn));
    }

    public function testModuloExpression()
    {
        $this->assertEquals(1.0, mod(expr(7), expr(2))->run($this->conn));
    }

    public function testAndExpressionTrueTrue()
    {
        $this->assertTrue(rAnd(expr(true), expr(true))->run($this->conn));
    }

    public function testAndExpressionTrueFalse()
    {
        $this->assertFalse(rAnd(expr(true), expr(false))->run($this->conn));
    }

    public function testAndExpressionFalseTrue()
    {
        $this->assertFalse(rAnd(expr(false), expr(true))->run($this->conn));
    }

    public function testAndExpressionFalseFalse()
    {
        $this->assertFalse(rAnd(expr(false), expr(false))->run($this->conn));
    }

    public function testOrExpressionTrueTrue()
    {
        $this->assertTrue(rOr(expr(true), expr(true))->run($this->conn));
    }

    public function testOrExpressionTrueFalse()
    {
        $this->assertTrue(rOr(expr(true), expr(false))->run($this->conn));
    }

    public function testOrExpressionFalseTrue()
    {
        $this->assertTrue(rOr(expr(false), expr(true))->run($this->conn));
    }

    public function testOrExpressionFalseFalse()
    {
        $this->assertFalse(rOr(expr(false), expr(false))->run($this->conn));
    }

    public function testEqExpressionFalse()
    {
        $this->assertFalse(eq(expr(5), expr(6))->run($this->conn));
    }

    public function testEqExpressionTrue()
    {
        $this->assertTrue(eq(expr(6), expr(6))->run($this->conn));
    }

    public function testNeExpressionTrue()
    {
        $this->assertTrue(ne(expr(5), expr(6))->run($this->conn));
    }

    public function testNeExpressionFalse()
    {
        $this->assertFalse(ne(expr(6), expr(6))->run($this->conn));
    }

    public function testGtExpressionFalse()
    {
        $this->assertFalse(gt(expr(5), expr(6))->run($this->conn));
    }

    public function testGtExpressionFalseEq()
    {
        $this->assertFalse(gt(expr(6), expr(6))->run($this->conn));
    }

    public function testGtExpressionTrue()
    {
        $this->assertTrue(gt(expr(6), expr(5))->run($this->conn));
    }

    public function testGeExpressionFalse()
    {
        $this->assertFalse(ge(expr(5), expr(6))->run($this->conn));
    }

    public function testGeExpressionTrueEq()
    {
        $this->assertTrue(ge(expr(6), expr(6))->run($this->conn));
    }

    public function testGeExpressionTrue()
    {
        $this->assertTrue(ge(expr(6), expr(5))->run($this->conn));
    }

    public function testLtExpressionTrue()
    {
        $this->assertTrue(lt(expr(5), expr(6))->run($this->conn));
    }

    public function testLtExpressionFalseEq()
    {
        $this->assertFalse(lt(expr(6), expr(6))->run($this->conn));
    }

    public function testLtExpressionFalse()
    {
        $this->assertFalse(lt(expr(6), expr(5))->run($this->conn));
    }

    public function testLeExpressionTrue()
    {
        $this->assertTrue(le(expr(5), expr(6))->run($this->conn));
    }

    public function testLeExpressionTrueEq()
    {
        $this->assertTrue(le(expr(6), expr(6))->run($this->conn));
    }

    public function testLeExpressionFalse()
    {
        $this->assertFalse(le(expr(6), expr(5))->run($this->conn));
    }

    public function testNotExpressionFalse()
    {
        $this->assertFalse(not(expr(true))->run($this->conn));
    }

    public function testNotExpressionTrue()
    {
        $this->assertTrue(not(expr(false))->run($this->conn));
    }

    public function testRandom()
    {
        for ($i = 0; $i < 10; ++$i) {
            $this->assertTrue(random()->lt(1.0)->run($this->conn));
            $this->assertTrue(random()->ge(0.0)->run($this->conn));
        }
    }

    public function testRandomMax()
    {
        for ($i = 0; $i < 10; ++$i) {
            $this->assertTrue(random(10)->lt(10)->run($this->conn));
            $this->assertTrue(random(10)->ge(0.0)->run($this->conn));
        }
    }

    public function testRandomRange()
    {
        for ($i = 0; $i < 10; ++$i) {
            $this->assertTrue(random(5, 10)->lt(10)->run($this->conn));
            $this->assertTrue(random(5, 10)->ge(5.0)->run($this->conn));
        }
    }

    public function testCeil()
    {
        $this->assertEquals(2.0, ceil(1.5)->run($this->conn));
    }

    public function testFloor()
    {
        $this->assertEquals(1.0, floor(1.5)->run($this->conn));
    }

    public function testRound()
    {
        $this->assertEquals(1.0, round(1.4)->run($this->conn));
        $this->assertEquals(2.0, round(1.5)->run($this->conn));
    }

    public function testExprCeil()
    {
        $this->assertEquals(2.0, expr(1.5)->ceil()->run($this->conn));
    }

    public function testExprFloor()
    {
        $this->assertEquals(1.0, expr(1.5)->floor()->run($this->conn));
    }

    public function testExprRound()
    {
        $this->assertEquals(2.0, expr(1.5)->round()->run($this->conn));
    }
}
