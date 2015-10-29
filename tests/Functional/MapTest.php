<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

// use function \r\row;
// use function \r\expr;
// use function \r\range;
// use function \r\branch;
// use function \r\mapMultiple;

class MapTest extends TestCase
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

    public function testMap()
    {
        $res = $this->db()->table('marvel')->map(function ($hero) {
                return $hero('combatPower')->add($hero('compassionPower')->mul(2));
        })
            ->run($this->conn);

        $this->assertEquals(array(7.0, 9.0, 5.0), $res->toArray());
    }

    public function testMapRow()
    {
        $res = $this->db()->table('marvel')
            ->map(\r\row('combatPower')->add(\r\row('compassionPower')->mul(2)))
            ->run($this->conn);

        $this->assertEquals(array(7.0, 9.0, 5.0), $res->toArray());
    }

    public function testCoerceToMapFunc()
    {
        $res = \r\expr(
            array(
                    $this->db()->table('marvel')->coerceTo('array'),
                    $this->db()->table('marvel')->coerceTo('array')
                )
        )->concatMap(function ($hero) {
            return $hero->pluck('superhero');
        })
            ->map(\r\row('superhero'))
            ->run($this->conn);

        $this->assertEquals(
            array('Spiderman', 'Wolverine', 'Iron Man', 'Spiderman', 'Wolverine', 'Iron Man'),
            (array)$res
        );
    }

    public function testCoerceToMap()
    {
        $res = \r\expr(
            array(
                    $this->db()->table('marvel')->coerceTo('array'),
                    $this->db()->table('marvel')->coerceTo('array')
                )
        )->concatMap(\r\row()->pluck('superhero'))
            ->map(\r\row('superhero'))
            ->run($this->conn);

        $this->assertEquals(
            array('Spiderman', 'Wolverine', 'Iron Man', 'Spiderman', 'Wolverine', 'Iron Man'),
            (array)$res
        );
    }

    public function testRegression62()
    {
        $res = \r\expr(array(1, 2, 3))
            ->map(
                \r\branch(
                    \r\expr(true),
                    function ($x) {
                        return $x;
                    },
                    function ($x) {
                        return $x;
                    }
                )
            )->run($this->conn);

        $this->assertEquals(array(1.0, 2.0, 3.0), (array)$res);
    }

    public function testMapMultipleRange()
    {
        $res = \r\mapMultiple(
            array(
                    \r\range(1, 4),
                    \r\range(2, 5)
                ),
            function ($x, $y) {
                return $x->add($y);
            }
        )->run($this->conn);

        $this->assertEquals(array(3, 5, 7), $res->toArray());
    }

    public function tesRangetMapMultiple()
    {
        $res = \r\range(1, 4)
            ->mapMultiple(
                array(
                    \r\range(2, 5)
                ),
                function ($x, $y) {
                    return $x->add($y);
                }
            )->run($this->conn);

        $this->assertEquals(array(3, 5, 7), (array)$res);
    }

    public function tesRangetMapMultipleFunc()
    {
        $res = \r\range(1, 4)
            ->mapMultiple(\r\range(2, 5), function ($x, $y) {
                return $x->add($y);
            })
            ->run($this->conn);

        $this->assertEquals(array(3, 5, 7), $res->toArray());
    }

    public function testMapMultipleRangeAddSub()
    {
        $res = \r\range(1, 4)
            ->mapMultiple(
                array(
                    \r\range(2, 5),
                    \r\range(1, 4)
                ),
                function ($x, $y, $z) {
                    return $x->add($y)->sub($z);
                }
            )->run($this->conn);

        $this->assertEquals(array(2, 3, 4), $res->toArray());
    }
}
