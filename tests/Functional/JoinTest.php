<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

class JoinTest extends TestCase
{
    public function setUp()
    {
        $this->conn = $this->getConnection();
        $this->data = $this->useDataset('Joins');
        $this->data->populate();
    }

    public function tearDown()
    {
        $this->data->truncate();
    }

    public function testInnerJoinT1()
    {
        $excpected = array(
            array(
                'left'  => array('id' => 1,   'other' => 'a'),
                'right' => array('id' => 'a', 'other' => 1 )
            ),
            array(
                'left'  => array('id' => 2,   'other' => 'a'),
                'right' => array('id' => 'a', 'other' => 1 )
            ),
            array(
                'left'  => array('id' => 3,   'other' => 'b'),
                'right' => array('id' => 'b', 'other' => 1 )
            )
        );

        $res = $this->db()->table('t1')
            ->innerJoin(
                $this->db()->table('t2'),
                function ($r1, $r2) {
                    return $r1('other')->eq($r2('id'));
                }
            )
            ->orderBy(array(function($x) { return $x("left")->getField("id"); }, function($x) { return $x("right")->getField("id"); }))
            ->run($this->conn);

        $this->assertEquals($excpected, $this->orderArrayByLeftAndRightId($res));
    }

    public function testInnerJoinT2()
    {
        $excpected = array(
            array(
                'right' => array('id' => 1,   'other' => 'a'),
                'left'  => array('id' => 'a', 'other' => 1 )
            ),
            array(
                'right' => array('id' => 1,   'other' => 'a'),
                'left'  => array('id' => 'b', 'other' => 1 )
            )
        );

        $res = $this->db()->table('t2')
            ->innerJoin(
                $this->db()->table('t1'),
                function ($r1, $r2) {
                    return $r1('other')->eq($r2('id'));
                }
            )
            ->orderBy(array(function($x) { return $x("left")->getField("id"); }, function($x) { return $x("right")->getField("id"); }))
            ->run($this->conn);

        $this->assertEquals($excpected, $this->orderArrayByLeftAndRightId($res));
    }

    public function testOuterJoinT1()
    {
        $excpected = array(
            array(
                'left'  => array('id' => 1,   'other' => 'a'),
                'right' => array('id' => 'a', 'other' => 1 )
            ),
            array(
                'left'  => array('id' => 2,   'other' => 'a'),
                'right' => array('id' => 'a', 'other' => 1 )
            ),
            array(
                'left'  => array('id' => 3,   'other' => 'b'),
                'right' => array('id' => 'b', 'other' => 1 )
            )
        );

        $res = $this->db()->table('t1')
            ->outerJoin(
                $this->db()->table('t2'),
                function ($r1, $r2) {
                    return $r1('other')->eq($r2('id'));
                }
            )
            ->orderBy(array(function($x) { return $x("left")->getField("id"); }, function($x) { return $x("right")->getField("id"); }))
            ->run($this->conn);

        $this->assertEquals($excpected, $this->orderArrayByLeftAndRightId($res));
    }

    public function testOuterJoinT2()
    {
        $excpected = array(
            array(
                'right' => array('id' => 1, 'other' => 'a'),
                'left' => array('id' => 'a', 'other' => 1 )
            ),
            array(
                'right' => array('id' => 1, 'other' => 'a'),
                'left' => array('id' => 'b', 'other' => 1 )
            ),
            array('left' => array('id' => 'c', 'other' => 5 ))
        );

        $res = $this->db()->table('t2')
            ->outerJoin(
                $this->db()->table('t1'),
                function ($r1, $r2) {
                    return $r1('other')->eq($r2('id'));
                }
            )
            ->orderBy(array(function($x) { return $x("left")->getField("id"); }, function($x) { return $x("right")->getField("id"); }))
            ->run($this->conn);

        $this->assertEquals($excpected, $this->orderArrayByLeftAndRightId($res));
    }

    public function testEqJoinOther()
    {
        $excpected = array(
            array(
                'left' => array('id' => 1, 'other' => 'a'),
                'right' => array('id' => 'a', 'other' => 1 )
            ),
            array(
                'left' => array('id' => 2, 'other' => 'a'),
                'right' => array('id' => 'a', 'other' => 1 )
            ),
            array(
                'left' => array('id' => 3, 'other' => 'b'),
                'right' => array('id' => 'b', 'other' => 1 )
            )
        );

        $res = $this->db()->table('t1')
            ->eqJoin('other', $this->db()->table('t2'))
            ->orderBy(array(function($x) { return $x("left")->getField("id"); }, function($x) { return $x("right")->getField("id"); }))
            ->run($this->conn);

        $this->assertEquals($excpected, $this->orderArrayByLeftAndRightId($res));
    }

    public function testEqJoinId()
    {
        $excpected = array(
            array(
                'left' => array('id' => 1, 'other' => 'a'),
                'right' => array('id' => 'a', 'other' => 1 )
            ),
            array(
                'left' => array('id' => 1, 'other' => 'a'),
                'right' => array('id' => 'b', 'other' => 1 )
            ),
        );

        $res = $this->db()->table('t1')
            ->eqJoin('id', $this->db()->table('t2'), array('index' => 'other'))
            ->orderBy(array(function($x) { return $x("left")->getField("id"); }, function($x) { return $x("right")->getField("id"); }))
            ->run($this->conn);

        $this->assertEquals($excpected, $this->orderArrayByLeftAndRightId($res));
    }

    public function testEqJoinFunc()
    {
        $excpected = array(
            array(
                'left' => array('id'  => 1, 'other'   => 'a'),
                'right' => array('id' => 'a', 'other' => 1 )
            ),
            array(
                'left' => array('id'  => 2, 'other'   => 'a'),
                'right' => array('id' => 'a', 'other' => 1 )
            ),
            array(
                'left' => array('id'  => 3, 'other'   => 'b'),
                'right' => array('id' => 'b', 'other' => 1 )
            )
        );

        $res = $this->db()->table('t1')
            ->eqJoin(function ($x) {
                return $x('other');
            }, $this->db()->table('t2'))
            ->orderBy(array(function($x) { return $x("left")->getField("id"); }, function($x) { return $x("right")->getField("id"); }))
            ->run($this->conn);

        $this->assertEquals($excpected, $this->orderArrayByLeftAndRightId($res));
    }

    public function testEqJoinZip()
    {
        $excpected = array(
            array('id' => 'a', 'other' => 1 ),
            array('id' => 'b', 'other' => 1 )
        );

        $res = $this->db()->table('t1')
            ->eqJoin('id', $this->db()->table('t2'), array('index' => 'other'))
            ->zip()
            ->orderBy("id")
            ->run($this->conn);

        $this->assertEquals($excpected, $this->orderArrayById($res));
    }

    protected function orderArrayByLeftAndRightId($data)
    {
        $data = $this->toArray($data);
        usort($data, function ($a, $b) {
            if ($a['left']['id'] == $b['left']['id']) {
                return $a['right']['id'] > $b['right']['id'];
            }
            return $a['left']['id'] > $b['left']['id'];
        });

        return $data;
    }

    protected function orderArrayById($data)
    {
        $data = $this->toArray($data);
        usort($data, function ($a, $b) {
            return strcmp($a['id'], $b['id']);
        });

        return $data;
    }
}
