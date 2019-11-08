<?php

namespace r\Tests\Functional;

use function r\expr;
use function r\literal;
use function r\row;
use r\Tests\TestCase;

class ManipulationsTest extends TestCase
{
    public function testFilterGetFieldPluck()
    {
        $res = expr([['x' => 1, 'y' => 2]])->filter(row()->getField('y')->eq(2))->pluck('x')->run($this->conn);
        $this->assertEquals([['x' => 1]], $this->toArray($res));
    }

    public function testFilterPluck()
    {
        $res = expr([['x' => 1, 'y' => 2]])->filter(row('y')->eq(2))->pluck('x')->run($this->conn);
        $this->assertEquals([['x' => 1]], $this->toArray($res));
    }

    public function testPluck()
    {
        $res = expr([['x' => 1, 'y' => 2]])->pluck('x')->run($this->conn);
        $this->assertEquals([['x' => 1]], $this->toArray($res));
    }

    public function testPluckArray()
    {
        $res = expr([['x' => 1, 'y' => 2]])->pluck('x', 'y')->run($this->conn);
        $this->assertEquals([['x' => 1, 'y' => 2]], $this->toArray($res));
    }

    public function testPluckArrayTrue()
    {
        $res = expr([['x' => 1, 'y' => ['a' => 2.1, 'b' => 2.2]]])->pluck(['x' => true])->run($this->conn);
        $this->assertEquals([['x' => 1]], $this->toArray($res));
    }

    public function testPluckArrayArray()
    {
        $res = expr([['x' => 1, 'y' => ['a' => 2.1, 'b' => 2.2]]])->pluck(['y' => ['a', 'b']])->run($this->conn);
        $this->assertEquals([['y' => ['a' => 2.1, 'b' => 2.2]]], $this->toArray($res));
    }

    public function testPluckArrayArrayTrue()
    {
        $res = expr([['x' => 1, 'y' => ['a' => 2.1, 'b' => 2.2]]])->pluck(['y' => ['b' => true]])->run($this->conn);
        $this->assertEquals([['y' => ['b' => 2.2]]], $this->toArray($res));
    }

    public function testWithout()
    {
        $res = expr([['x' => 1, 'y' => 2]])->without('x')->run($this->conn);
        $this->assertEquals([['y' => 2]], $this->toArray($res));
    }

    public function testWithoutArray()
    {
        $res = expr([['x' => 1, 'y' => 2]])->without('x', 'y')->run($this->conn);
        $this->assertEquals([[]], $this->toArray($res));
    }

    public function testWithoutArrayTrue()
    {
        $res = expr([['x' => 1, 'y' => ['a' => 2.1, 'b' => 2.2]]])->without(['x' => true])->run($this->conn);
        $this->assertEquals([['y' => ['a' => 2.1, 'b' => 2.2]]], $this->toArray($res));
    }

    public function testWithoutArrayArray()
    {
        $res = expr([['x' => 1, 'y' => ['a' => 2.1, 'b' => 2.2]]])->without(['y' => ['a', 'b']])->run($this->conn);
        $this->assertEquals([['x' => 1, 'y' => []]], $this->toArray($res));
    }

    public function testWithoutArrayArrayTrue()
    {
        $res = expr([['x' => 1, 'y' => ['a' => 2.1, 'b' => 2.2]]])->without(['y' => ['b' => true]])->run($this->conn);
        $this->assertEquals([['x' => 1, 'y' => ['a' => 2.1]]], $this->toArray($res));
    }

    public function testMerge()
    {
        $res = expr(['x' => 1])->merge(['y' => 2])->run($this->conn);
        $this->assertEquals(['x' => 1, 'y' => 2], $this->toArray($res));
    }

    public function testMergeExpr()
    {
        $res = expr(['x' => 1])->merge(expr(['y' => 2]))->run($this->conn);
        $this->assertEquals(['x' => 1, 'y' => 2], $this->toArray($res));
    }

    public function testArrayMerge()
    {
        $res = expr(['x' => 1, 'y' => ['a' => 1, 'b' => 2]])->merge(['y' => ['c' => 3]])->run($this->conn);
        $this->assertEquals(['x' => 1, 'y' => ['a' => 1, 'b' => 2, 'c' => 3]], $this->toArray($res));
    }

    public function testArrayMergeLiteralArray()
    {
        $res = expr(['x' => 1, 'y' => ['a' => 1, 'b' => 2]])->merge(['y' => literal(['c' => 3])])->run($this->conn);
        $this->assertEquals(['x' => 1, 'y' => ['c' => 3]], $this->toArray($res));
    }

    public function testArrayMergeLiteral()
    {
        $res = expr(['x' => 1, 'y' => ['a' => 1, 'b' => 2]])->merge(['y' => literal()])->run($this->conn);
        $this->assertEquals(['x' => 1], (array) $res);
    }

    public function testArrayMergeFunction()
    {
        $res = expr([['a' => 1], ['a' => 2]])->merge(function ($doc) {
            return ['b' => $doc('a')->add(1)];
        })->run($this->conn);
        $this->assertEquals([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3]], $this->toArray($res));
    }

    public function testAppend()
    {
        $res = expr([1, 2, 3])->append(4)->run($this->conn);
        $this->assertEquals([1, 2, 3, 4], (array) $res);
    }

    public function testAppendExpr()
    {
        $res = expr([1, 2, 3])->append(expr(4))->run($this->conn);
        $this->assertEquals([1, 2, 3, 4], (array) $res);
    }

    public function testPrepend()
    {
        $res = expr([1, 2, 3])->prepend(4)->run($this->conn);
        $this->assertEquals([4, 1, 2, 3], (array) $res);
    }

    public function testPrependExpr()
    {
        $res = expr([1, 2, 3])->prepend(expr(4))->run($this->conn);
        $this->assertEquals([4, 1, 2, 3], (array) $res);
    }

    public function testDifference()
    {
        $res = expr([1, 2, 3])->difference([1, 2])->run($this->conn);
        $this->assertEquals([3], (array) $res);
    }

    public function testDifferenceExpr()
    {
        $res = expr([1, 2, 3])->difference(expr([1, 2]))->run($this->conn);
        $this->assertEquals([3], (array) $res);
    }

    public function testhasField()
    {
        $this->assertTrue(expr(['x' => 1, 'y' => 2])->hasFields('x')->run($this->conn));
    }

    public function testHasFieldString()
    {
        $this->assertFalse(expr(['x' => 1, 'y' => 2])->hasFields('foo')->run($this->conn));
    }

    public function testHasFieldArray()
    {
        $this->assertTrue(expr(['x' => 1, 'y' => 2])->hasFields(['x', 'y'])->run($this->conn));
    }

    public function testHasFieldArrayString()
    {
        $this->assertFalse(expr(['x' => 1, 'y' => 2])->hasFields(['x', 'foo'])->run($this->conn));
    }

    public function testHasFieldArrayTrue()
    {
        $this->assertTrue(expr(['x' => 1, 'y' => 2])->hasFields(['x' => true])->run($this->conn));
    }

    public function testHasFieldArrayStringTrue()
    {
        $this->assertFalse(expr(['x' => 1, 'y' => 2])->hasFields(['foo' => true])->run($this->conn));
    }

    public function testSetInsert()
    {
        $res = expr([1, 2, 3])->setInsert(4)->run($this->conn);
        $this->assertEquals([1, 2, 3, 4], (array) $res);
    }

    public function testSetInsertDuplicate()
    {
        $res = expr([1, 2, 3])->setInsert(1)->run($this->conn);
        $this->assertEquals([1, 2, 3], (array) $res);
    }

    public function testSetUnion()
    {
        $res = expr([1, 2, 3])->setUnion([1, 4])->run($this->conn);
        $this->assertEquals([1, 2, 3, 4], (array) $res);
    }

    public function testSetIntersection()
    {
        $res = expr([1, 2, 3])->setIntersection([1, 4])->run($this->conn);
        $this->assertEquals([1], (array) $res);
    }

    public function testSetDifference()
    {
        $res = expr([1, 2, 3])->setDifference([1, 4])->run($this->conn);
        $this->assertEquals([2, 3], (array) $res);
    }

    public function testKeys()
    {
        $res = expr(['a' => 1, 'b' => 2, 'c' => 3])->keys()->run($this->conn);
        $this->assertEquals(['a', 'b', 'c'], (array) $res);
    }

    public function testValues()
    {
        $res = expr(['a' => 1, 'b' => 2, 'c' => 3])->values()->run($this->conn);
        $this->assertEquals([1, 2, 3], (array) $res);
    }

    public function testInsertAt()
    {
        $res = expr(['Iron Man', 'Spider-Man'])->insertAt(1, 'Hulk')->run($this->conn);
        $this->assertEquals(['Iron Man', 'Hulk', 'Spider-Man'], (array) $res);
    }

    public function testSpliceAt()
    {
        $res = expr(['Iron Man', 'Spider-Man'])->spliceAt(1, ['Hulk', 'Thor'])->run($this->conn);
        $this->assertEquals(['Iron Man', 'Hulk', 'Thor', 'Spider-Man'], (array) $res);
    }

    public function testDeleteAt()
    {
        $res = expr(['Iron Man', 'Hulk', 'Spider-Man'])->deleteAt(1)->run($this->conn);
        $this->assertEquals(['Iron Man', 'Spider-Man'], (array) $res);
    }

    public function testDeleteAtRange()
    {
        $res = expr(['Iron Man', 'Hulk', 'Thor', 'Spider-Man'])->deleteAt(1, 2)->run($this->conn);
        $this->assertEquals(['Iron Man', 'Thor', 'Spider-Man'], (array) $res);
    }

    // TODO: This is disabled due to a potential bug in the server as of
    // RethinkDB 1.9.0: https://github.com/rethinkdb/rethinkdb/issues/1456
    /*public function testDeleteRightBound()
          {
              $res = \r\expr(array('Iron Man', 'Hulk', 'Thor', 'Spider-Man'))
                  ->deleteAt(1,2, array('right_bound' => 'closed'))
                  ->run($this->conn);

              $this->assertEquals(array('Iron Man', 'Spider-Man'), (array)$res);
          }*/
    public function testChangeAt()
    {
        $res = expr(['Iron Man', 'Bruce', 'Spider-Man'])->changeAt(1, 'Hulk')->run($this->conn);
        $this->assertEquals(['Iron Man', 'Hulk', 'Spider-Man'], (array) $res);
    }
}
