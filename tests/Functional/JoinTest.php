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
        $expected = array(
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
            ->run($this->conn);

        $this->assertEquals($expected, $this->orderArrayByLeftAndRightId($res));
    }

    public function testInnerJoinT2()
    {
        $expected = array(
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
            ->run($this->conn);

        $this->assertEquals($expected, $this->orderArrayByLeftAndRightId($res));
    }

    public function testOuterJoinT1()
    {
        $expected = array(
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
            ->run($this->conn);

        $this->assertEquals($expected, $this->orderArrayByLeftAndRightId($res));
    }

    public function testOuterJoinT2()
    {
        $expected = array(
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
            ->run($this->conn);

        $this->assertEquals($expected, $this->orderArrayByLeftAndRightId($res));
    }

    public function testEqJoinOther()
    {
        $expected = array(
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
            ->run($this->conn);

        $this->assertEquals($expected, $this->orderArrayByLeftAndRightId($res));
    }

    public function testEqJoinId()
    {
        $expected = array(
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
            ->run($this->conn);

        $this->assertEquals($expected, $this->orderArrayByLeftAndRightId($res));
    }

    public function testEqJoinFunc()
    {
        $expected = array(
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
            ->run($this->conn);

        $this->assertEquals($expected, $this->orderArrayByLeftAndRightId($res));
    }

    public function testEqJoinZip()
    {
        $expected = array(
            array('id' => 'a', 'other' => 1 ),
            array('id' => 'b', 'other' => 1 )
        );

        $res = $this->db()->table('t1')
            ->eqJoin('id', $this->db()->table('t2'), array('index' => 'other'))
            ->zip()
            ->run($this->conn);

        $this->assertEquals($expected, $this->orderArrayById($res));
    }

    protected function orderArrayByLeftAndRightId($data)
    {
        $data = $this->toArray($data->toArray());
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
        $data = $this->toArray($data->toArray());
        usort($data, function ($a, $b) {
            return strcmp($a['id'], $b['id']);
        });

        return $data;
    }
}
