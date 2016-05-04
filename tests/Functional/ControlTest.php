<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

// use function \r\rDo;
// use function \r\row;
// use function \r\branch;
// use function \r\expr;
// use function \r\error;
// use function \r\range;

class ControlTest extends TestCase
{
    public function setUp()
    {
        $this->conn = $this->getConnection();
        $this->data = $this->useDataset('Heroes');
        $this->data2 = $this->useDataset('Control');
        $this->data->populate();
    }

    public function tearDown()
    {
        $this->data->truncate();
        $this->data2->truncate();
    }

    public function testDo()
    {
        $this->assertEquals(
            5.0,
            \r\rDo(
                array(1, 2, 3),
                function ($x, $y, $z) {
                    return $x->mul($y->add($z));
                }
            )->run($this->conn)
        );
    }

    public function testBranchTrue()
    {
        $this->assertEquals(
            'true',
            \r\branch(\r\expr(true), \r\expr('true'), \r\expr('false'))->run($this->conn)
        );
    }

    public function testBranchFalse()
    {
        $this->assertEquals(
            'false',
            \r\branch(\r\expr(false), \r\expr('true'), \r\expr('false'))->run($this->conn)
        );
    }

    public function testForEach()
    {
        $db = $this->db();
        $res = \r\expr(array(1, 2, 3))
            ->rForeach(
                function ($x) use ($db) {
                    return $db->table('t1')->insert(array('x' => $x));
                }
            )->getField('inserted')
            ->run($this->conn);

        $this->assertEquals(3.0, $res);
    }

    public function testMap()
    {
        $this->db()->table('t1')
            ->insert(array(
                array('x' => 1),
                array('x' => 2),
                array('x' => 3)
            ))->run($this->conn);

        $res = $this->db()->table('t1')->map(\r\row('x'))->run($this->conn)->toArray();

        sort($res);
        $this->assertEquals(array(1.0, 2.0, 3.0), $res);
    }

    public function testError()
    {
        $this->setExpectedException(
            '\r\Exceptions\RqlServerError',
            'Runtime error: ERRRRRR'
        );

        \r\error('ERRRRRR')->run($this->conn);
    }

    public function testToNumber()
    {
        $this->assertEquals(5.0, \r\expr('5.0')->coerceTo('number')->run($this->conn));
    }

    public function testToString()
    {
        $this->assertEquals('5', \r\expr(5.0)->coerceTo('string')->run($this->conn));
    }

    public function testTypeofNumber()
    {
        $this->assertEquals('NUMBER', \r\expr(5.0)->typeOf()->run($this->conn));
    }

    public function testTypeofString()
    {
        $this->assertEquals('STRING', \r\expr('foo')->typeOf()->run($this->conn));
    }

    public function testTypeofNull()
    {
        $this->assertEquals('NULL', \r\expr(null)->typeOf()->run($this->conn));
    }

    public function testTypeofArray()
    {
        $this->assertEquals('ARRAY', \r\expr(array(1, 2, 3))->typeOf()->run($this->conn));
    }

    public function testTypeofObject()
    {
        $this->assertEquals('OBJECT', \r\expr(array('x' => 1))->typeOf()->run($this->conn));
    }

    public function testTableInfo()
    {
        $res = $this->db()->table('marvel')
            ->info()
            ->pluck(array('type', 'name'))
            ->run($this->conn);

        $this->assertEquals(array('type' => 'TABLE', 'name' => 'marvel'), (array)$res);
    }

    public function testGetFieldA()
    {
        $this->assertEquals(
            4.0,
            \r\expr(array('a' => 4))->getField('a')->rDefault(5)->run($this->conn)
        );
    }

    public function testGetFieldB()
    {
        $this->assertEquals(
            5.0,
            \r\expr(array('a' => 4))->getField('b')->rDefault(5)->run($this->conn)
        );
    }

    public function testGetFieldFunction()
    {
        $this->assertEquals(
            5.0,
            \r\expr(array('a' => 4))
                ->getField('b')
                ->rDefault(function ($e) {
                    return \r\expr(5);
                })
                ->run($this->conn)
        );
    }

    public function testRange()
    {
        $this->assertEquals(array(0, 1, 2), \r\range(3)->run($this->conn)->toArray());
    }

    public function testSlice()
    {
        $this->assertEquals(array(1, 2), \r\range(1, 3)->run($this->conn)->toArray());
    }

    public function testRangeLimit()
    {
        $this->assertEquals(array(0, 1, 2), \r\range()->limit(3)->run($this->conn)->toArray());
    }

    public function testToJson()
    {
        $this->assertEquals('"123"', \r\expr('123')->toJsonString()->run($this->conn));
    }
}
