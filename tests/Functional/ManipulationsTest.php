<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

// use function \r\row;
// use function \r\expr;
// use function \r\literal;

class ManipulationsTest extends TestCase
{
    public function testFilterGetFieldPluck()
    {
        $res = \r\expr(array(array('x' => 1, 'y' => 2)))
            ->filter(\r\row()->getField('y')->eq(2))
            ->pluck('x')
            ->run($this->conn);

        $this->assertEquals(array(array('x' => 1)), $this->toArray($res));
    }

    public function testFilterPluck()
    {
        $res = \r\expr(array(array('x' => 1, 'y' => 2)))
            ->filter(\r\row('y')->eq(2))
            ->pluck('x')
            ->run($this->conn);

        $this->assertEquals(array(array('x' => 1)), $this->toArray($res));
    }

    public function testPluck()
    {
        $res = \r\expr(array(array('x' => 1, 'y' => 2)))
            ->pluck('x')
            ->run($this->conn);

        $this->assertEquals(array(array('x' => 1)), $this->toArray($res));
    }

    public function testPluckArray()
    {
        $res = \r\expr(array(array('x' => 1, 'y' => 2)))
            ->pluck(array('x', 'y'))
            ->run($this->conn);

        $this->assertEquals(array(array('x' => 1, 'y' => 2)), $this->toArray($res));
    }

    public function testPluckArrayTrue()
    {
        $res = \r\expr(
            array(array('x' => 1, 'y' => array('a' => 2.1, 'b' => 2.2)))
        )->pluck(array('x' => true))
            ->run($this->conn);

        $this->assertEquals(array(array('x' => 1)), $this->toArray($res));
    }

    public function testPluckArrayArray()
    {
        $res = \r\expr(
            array(array('x' => 1, 'y' => array('a' => 2.1, 'b' => 2.2)))
        )->pluck(array('y' => array('a', 'b')))
            ->run($this->conn);

        $this->assertEquals(
            array(array('y' => array('a' => 2.1, 'b' => 2.2))),
            $this->toArray($res)
        );
    }

    public function testPluckArrayArrayTrue()
    {
        $res = \r\expr(
            array(array('x' => 1, 'y' => array('a' => 2.1, 'b' => 2.2)))
        )->pluck(array('y' => array('b' => true)))
            ->run($this->conn);

        $this->assertEquals(
            array(array('y' => array('b' => 2.2))),
            $this->toArray($res)
        );
    }

    public function testWithout()
    {
        $res = \r\expr(array(array('x' => 1, 'y' => 2)))->without('x')
            ->run($this->conn);

        $this->assertEquals(array(array('y' => 2)), $this->toArray($res));
    }

    public function testWithoutArray()
    {
        $res = \r\expr(array(array('x' => 1, 'y' => 2)))->without(array('x', 'y'))
            ->run($this->conn);

        $this->assertEquals(array(array()), $this->toArray($res));
    }

    public function testWithoutArrayTrue()
    {
        $res = \r\expr(array(array('x' => 1, 'y' => array('a' => 2.1, 'b' => 2.2))))
            ->without(array('x' => true))
            ->run($this->conn);

        $this->assertEquals(
            array(array('y' => array('a' => 2.1, 'b' => 2.2))),
            $this->toArray($res)
        );
    }

    public function testWithoutArrayArray()
    {
        $res = \r\expr(array(array('x' => 1, 'y' => array('a' => 2.1, 'b' => 2.2))))
            ->without(array('y' => array('a', 'b')))
            ->run($this->conn);

        $this->assertEquals(
            array(array('x' => 1, 'y' => array())),
            $this->toArray($res)
        );
    }

    public function testWithoutArrayArrayTrue()
    {
        $res = \r\expr(array(array('x' => 1, 'y' => array('a' => 2.1, 'b' => 2.2))))
            ->without(array('y' => array('b' => true)))
            ->run($this->conn);

        $this->assertEquals(
            array(array('x' => 1, 'y' => array('a' => 2.1))),
            $this->toArray($res)
        );
    }

    public function testMerge()
    {
        $res = \r\expr(array('x' => 1))->merge(array('y' => 2))->run($this->conn);

        $this->assertEquals(array('x' => 1, 'y' => 2), $this->toArray($res));
    }

    public function testMergeExpr()
    {
        $res = \r\expr(array('x' => 1))->merge(\r\expr(array('y' => 2)))->run($this->conn);

        $this->assertEquals(array('x' => 1, 'y' => 2), $this->toArray($res));
    }

    public function testArrayMerge()
    {
        $res = \r\expr(array('x' => 1, 'y' => array('a' => 1, 'b' => 2)))
            ->merge(array('y' => array('c' => 3)))
            ->run($this->conn);

        $this->assertEquals(
            array('x' => 1, 'y' => array('a' => 1, 'b' => 2, 'c' => 3)),
            $this->toArray($res)
        );
    }

    public function testArrayMergeLiteralArray()
    {
        $res = \r\expr(array('x' => 1, 'y' => array('a' => 1, 'b' => 2)))
            ->merge(array('y' => \r\literal(array('c' => 3))))
            ->run($this->conn);

        $this->assertEquals(
            array('x' => 1, 'y' => array('c' => 3)),
            $this->toArray($res)
        );
    }

    public function testArrayMergeLiteral()
    {
        $res = \r\expr(array('x' => 1, 'y' => array('a' => 1, 'b' => 2)))
            ->merge(array('y' => \r\literal()))
            ->run($this->conn);

        $this->assertEquals(array('x' => 1), (array)$res);
    }

    public function testArrayMergeFunction()
    {
        $res = \r\expr(array(array('a' => 1), array('a' => 2)))
            ->merge(function ($doc) {
                return array('b' => $doc('a')->add(1));
            })
            ->run($this->conn);

        $this->assertEquals(
            array(array('a' => 1, 'b' => 2), array('a' => 2, 'b' => 3)),
            $this->toArray($res)
        );
    }

    public function testAppend()
    {
        $res = \r\expr(array(1, 2, 3))->append(4)->run($this->conn);

        $this->assertEquals(array(1, 2, 3, 4), (array)$res);
    }

    public function testAppendExpr()
    {
        $res =\r\expr(array(1, 2, 3))->append(\r\expr(4))->run($this->conn);

        $this->assertEquals(array(1, 2, 3, 4), (array)$res);
    }

    public function testPrepend()
    {
        $res = \r\expr(array(1, 2, 3))->prepend(4)->run($this->conn);

        $this->assertEquals(array(4, 1, 2, 3), (array)$res);
    }

    public function testPrependExpr()
    {
        $res  =\r\expr(array(1, 2, 3))->prepend(\r\expr(4))->run($this->conn);

        $this->assertEquals(array(4, 1, 2, 3), (array)$res);
    }

    public function testDifference()
    {
        $res  = \r\expr(array(1, 2, 3))->difference(array(1, 2))->run($this->conn);

        $this->assertEquals(array(3), (array)$res);
    }

    public function testDifferenceExpr()
    {
        $res  = \r\expr(array(1, 2, 3))->difference(\r\expr(array(1, 2)))->run($this->conn);

        $this->assertEquals(array(3), (array)$res);
    }

    public function testhasField()
    {
        $this->assertTrue(
            \r\expr(array('x' => 1, 'y' => 2))->hasFields('x')->run($this->conn)
        );
    }

    public function testHasFieldString()
    {
        $this->assertFalse(
            \r\expr(array('x' => 1, 'y' => 2))->hasFields('foo')->run($this->conn)
        );
    }

    public function testHasFieldArray()
    {
        $this->assertTrue(
            \r\expr(array('x' => 1, 'y' => 2))
                ->hasFields(array('x', 'y'))
                ->run($this->conn)
        );
    }

    public function testHasFieldArrayString()
    {
        $this->assertFalse(
            \r\expr(array('x' => 1, 'y' => 2))
                ->hasFields(array('x', 'foo'))
                ->run($this->conn)
        );
    }

    public function testHasFieldArrayTrue()
    {
        $this->assertTrue(
            \r\expr(array('x' => 1, 'y' => 2))
                ->hasFields(array('x' => true))
                ->run($this->conn)
        );
    }

    public function testHasFieldArrayStringTrue()
    {
        $this->assertFalse(
            \r\expr(array('x' => 1, 'y' => 2))
                ->hasFields(array('foo' => true))
                ->run($this->conn)
        );
    }

    public function testSetInsert()
    {
        $res = \r\expr(array(1, 2, 3))->setInsert(4)->run($this->conn);

        $this->assertEquals(array(1, 2, 3, 4), (array)$res);
    }

    public function testSetInsertDuplicate()
    {
        $res = \r\expr(array(1, 2, 3))->setInsert(1)->run($this->conn);

        $this->assertEquals(array(1, 2, 3), (array)$res);
    }

    public function testSetUnion()
    {
        $res = \r\expr(array(1, 2, 3))->setUnion(array(1, 4))->run($this->conn);

        $this->assertEquals(array(1, 2, 3, 4), (array)$res);
    }

    public function testSetIntersection()
    {
        $res = \r\expr(array(1, 2, 3))->setIntersection(array(1, 4))->run($this->conn);

        $this->assertEquals(array(1), (array)$res);
    }

    public function testSetDifference()
    {
        $res = \r\expr(array(1, 2, 3))->setDifference(array(1, 4))->run($this->conn);

        $this->assertEquals(array(2, 3), (array)$res);
    }

    public function testKeys()
    {
        $res = \r\expr(array('a' => 1, 'b' => 2, 'c' => 3))->keys()->run($this->conn);

        $this->assertEquals(array('a', 'b', 'c'), (array)$res);
    }

    public function testValues()
    {
        $res = \r\expr(array('a' => 1, 'b' => 2, 'c' => 3))->values()->run($this->conn);

        $this->assertEquals(array(1, 2, 3), (array)$res);
    }

    public function testInsertAt()
    {
        $res = \r\expr(array('Iron Man', 'Spider-Man'))->insertAt(1, 'Hulk')->run($this->conn);

        $this->assertEquals(array('Iron Man', 'Hulk', 'Spider-Man'), (array)$res);
    }

    public function testSpliceAt()
    {
        $res = \r\expr(array('Iron Man', 'Spider-Man'))
            ->spliceAt(1, array('Hulk', 'Thor'))
            ->run($this->conn);

        $this->assertEquals(
            array('Iron Man', 'Hulk', 'Thor',  'Spider-Man'),
            (array)$res
        );
    }

    public function testDeleteAt()
    {
        $res = \r\expr(array('Iron Man', 'Hulk', 'Spider-Man'))->deleteAt(1)->run($this->conn);

        $this->assertEquals(array('Iron Man', 'Spider-Man'), (array)$res);
    }

    public function testDeleteAtRange()
    {
        $res = \r\expr(array('Iron Man', 'Hulk', 'Thor', 'Spider-Man'))
            ->deleteAt(1, 2)
            ->run($this->conn);

        $this->assertEquals(array('Iron Man', 'Thor', 'Spider-Man'), (array)$res);
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
        $res = \r\expr(array('Iron Man', 'Bruce', 'Spider-Man'))
            ->changeAt(1, 'Hulk')
            ->run($this->conn);

        $this->assertEquals(array('Iron Man', 'Hulk', 'Spider-Man'), (array)$res);
    }
}
