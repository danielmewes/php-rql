<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

class JoinTest extends TestCase
{
    protected function setUp(): void
    {
        $this->conn = $this->getConnection();
        $this->dataset = $this->useDataset('Joins');
        $this->dataset->populate();
    }

    protected function tearDown(): void
    {
        $this->dataset->truncate();
    }

    public function testInnerJoinT1()
    {
        $expected = [
            [
                'left' => ['id' => 1,   'other' => 'a'],
                'right' => ['id' => 'a', 'other' => 1],
            ],
            [
                'left' => ['id' => 2,   'other' => 'a'],
                'right' => ['id' => 'a', 'other' => 1],
            ],
            [
                'left' => ['id' => 3,   'other' => 'b'],
                'right' => ['id' => 'b', 'other' => 1],
            ],
        ];

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
        $expected = [
            [
                'right' => ['id' => 1,   'other' => 'a'],
                'left' => ['id' => 'a', 'other' => 1],
            ],
            [
                'right' => ['id' => 1,   'other' => 'a'],
                'left' => ['id' => 'b', 'other' => 1],
            ],
        ];

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
        $expected = [
            [
                'left' => ['id' => 1,   'other' => 'a'],
                'right' => ['id' => 'a', 'other' => 1],
            ],
            [
                'left' => ['id' => 2,   'other' => 'a'],
                'right' => ['id' => 'a', 'other' => 1],
            ],
            [
                'left' => ['id' => 3,   'other' => 'b'],
                'right' => ['id' => 'b', 'other' => 1],
            ],
        ];

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
        $expected = [
            [
                'right' => ['id' => 1, 'other' => 'a'],
                'left' => ['id' => 'a', 'other' => 1],
            ],
            [
                'right' => ['id' => 1, 'other' => 'a'],
                'left' => ['id' => 'b', 'other' => 1],
            ],
            ['left' => ['id' => 'c', 'other' => 5]],
        ];

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
        $expected = [
            [
                'left' => ['id' => 1, 'other' => 'a'],
                'right' => ['id' => 'a', 'other' => 1],
            ],
            [
                'left' => ['id' => 2, 'other' => 'a'],
                'right' => ['id' => 'a', 'other' => 1],
            ],
            [
                'left' => ['id' => 3, 'other' => 'b'],
                'right' => ['id' => 'b', 'other' => 1],
            ],
        ];

        $res = $this->db()->table('t1')
            ->eqJoin('other', $this->db()->table('t2'))
            ->run($this->conn);

        $this->assertEquals($expected, $this->orderArrayByLeftAndRightId($res));
    }

    public function testEqJoinId()
    {
        $expected = [
            [
                'left' => ['id' => 1, 'other' => 'a'],
                'right' => ['id' => 'a', 'other' => 1],
            ],
            [
                'left' => ['id' => 1, 'other' => 'a'],
                'right' => ['id' => 'b', 'other' => 1],
            ],
        ];

        $res = $this->db()->table('t1')
            ->eqJoin('id', $this->db()->table('t2'), ['index' => 'other'])
            ->run($this->conn);

        $this->assertEquals($expected, $this->orderArrayByLeftAndRightId($res));
    }

    public function testEqJoinFunc()
    {
        $expected = [
            [
                'left' => ['id' => 1, 'other' => 'a'],
                'right' => ['id' => 'a', 'other' => 1],
            ],
            [
                'left' => ['id' => 2, 'other' => 'a'],
                'right' => ['id' => 'a', 'other' => 1],
            ],
            [
                'left' => ['id' => 3, 'other' => 'b'],
                'right' => ['id' => 'b', 'other' => 1],
            ],
        ];

        $res = $this->db()->table('t1')
            ->eqJoin(function ($x) {
                return $x('other');
            }, $this->db()->table('t2'))
            ->run($this->conn);

        $this->assertEquals($expected, $this->orderArrayByLeftAndRightId($res));
    }

    public function testEqJoinZip()
    {
        $expected = [
            ['id' => 'a', 'other' => 1],
            ['id' => 'b', 'other' => 1],
        ];

        $res = $this->db()->table('t1')
            ->eqJoin('id', $this->db()->table('t2'), ['index' => 'other'])
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
