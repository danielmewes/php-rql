<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

// use function \r\row;
// use function \r\expr;
// use function \r\line;
// use function \r\point;
// use function \r\circle;
// use function \r\geojson;
// use function \r\polygon;

class GeoTest extends TestCase
{
    public function setUp()
    {
        $this->conn = $this->getConnection();
        $this->data = $this->useDataset('Geo');
        $this->data->populate();
    }

    public function tearDown()
    {
        $this->data->truncate();
    }

    public function testGeoJson()
    {
        $res = \r\geojson(
            array('type' => 'Point', 'coordinates' => array(0.0, 1.0))
        )->run($this->conn);

        $this->assertEquals(
            array('$reql_type$' => 'GEOMETRY', 'type' => 'Point', 'coordinates' => array(0.0, 1.0)),
            (array)$res
        );
    }

    public function testToGepJson()
    {
        $res = \r\expr(array(
                '$reql_type$' => 'GEOMETRY',
                'type' => 'Point',
                'coordinates' => array(0.0, 1.0)
            ))->toGeojson()
            ->run($this->conn);

        $this->assertEquals(
            array('type' => 'Point', 'coordinates' => array(0.0, 1.0)),
            (array)$res
        );
    }

    public function testPoint()
    {
        $res = \r\point(0.0, 1.0)->run($this->conn);

        $this->assertEquals(
            array(
                '$reql_type$' => 'GEOMETRY',
                'type' => 'Point',
                'coordinates' => array(0.0, 1.0)
            ),
            (array)$res
        );
    }

    public function testLine()
    {
        $res = \r\line(array(array(0.0, 0.0), array(0.0, 1.0), array(1.0, 1.0)))
            ->run($this->conn);

        $this->assertEquals(
            array(
                '$reql_type$' => 'GEOMETRY',
                'type' => 'LineString',
                'coordinates' => array(
                    array(0.0, 0.0),
                    array(0.0, 1.0),
                    array(1.0, 1.0)
                )
            ),
            (array)$res
        );
    }

    public function testLineFill()
    {
        $res = \r\line(array(array(0.0, 0.0), array(0.0, 1.0), array(1.0, 1.0)))
            ->fill()
            ->run($this->conn);

        $this->assertEquals(
            array(
                '$reql_type$' => 'GEOMETRY',
                'type' => 'Polygon',
                'coordinates' => array(
                    array(
                        array(0.0, 0.0),
                        array(0.0, 1.0),
                        array(1.0, 1.0),
                        array(0.0, 0.0)
                    )
                )
            ),
            (array)$res
        );
    }

    public function testPolygon()
    {
        $res = \r\polygon(array(array(0.0, 0.0), array(0.0, 1.0), array(1.0, 1.0)))
            ->run($this->conn);

        $this->assertEquals(
            array(
                '$reql_type$' => 'GEOMETRY',
                'type' => 'Polygon',
                'coordinates' => array(
                    array(
                        array(0.0, 0.0),
                        array(0.0, 1.0),
                        array(1.0, 1.0),
                        array(0.0, 0.0)
                    )
                )
            ),
            (array)$res
        );
    }

    public function testPolygonSub()
    {
        $res = \r\polygon(
            array(
                    array(0.0, 0.0),
                    array(0.0, 2.0),
                    array(2.0, 2.0),
                    array(2.0, 0.0)
                )
        )->polygonSub(
            \r\polygon(array(array(0.5, 0.5), array(0.5, 0.8), array(0.8, 0.8)))
        )->run($this->conn);

        $this->assertEquals(
            array(
                '$reql_type$' => 'GEOMETRY',
                'type' => 'Polygon',
                'coordinates' => array(
                    array(
                        array(0.0, 0.0),
                        array(0.0, 2.0),
                        array(2.0, 2.0),
                        array(2.0, 0.0),
                        array(0.0, 0.0)
                    ), array(
                        array(0.5, 0.5),
                        array(0.5, 0.8),
                        array(0.8, 0.8),
                        array(0.5, 0.5)
                    )
                )
            ),
            (array)$res
        );
    }

    // These might fail due to rounding issues, depending on the server's architecture
    // removing the . might help
    public function testDistance()
    {
        $res = \r\point(0.0, 1.0)
            ->distance(\r\point(1.0, 1.0))
            ->coerceTo("STRING")
            ->run($this->conn);

        $this->assertContains('1113026493394308', str_replace('.', '', $res));
    }

    public function testDistanceKm()
    {
        $res = \r\point(0.0, 1.0)->distance(
            \r\point(1.0, 1.0),
            array('unit' => 'km')
        )->coerceTo("STRING")->run($this->conn);

        $this->assertContains('1113026493394308', str_replace('.', '', $res));
    }

    public function testPolygonIntersects()
    {
        $this->assertTrue(
            \r\polygon(
                array(
                    array(0.0, 0.0),
                    array(0.0, 1.0),
                    array(1.0, 1.0)
                )
            )->intersects(
                \r\line(array(array(0.0, 0.0), array(2.0, 2.0)))
            )->run($this->conn)
        );
    }

    public function testPolygonIncludes()
    {
        $this->assertFalse(
            \r\polygon(
                array(
                    array(0.0, 0.0),
                    array(0.0, 1.0),
                    array(1.0, 1.0)
                )
            )->includes(
                \r\line(array(array(0.0, 0.0), array(2.0, 2.0)))
            )->run($this->conn)
        );
    }

    public function testCircle()
    {
        $this->assertFalse(
            \r\circle(\r\point(0.0, 0.0), 10.0)
                ->intersects(
                    \r\line(array(array(0.1, 0.0), array(2.0, 2.0)))
                )->run($this->conn)
        );
    }

    public function testCircleMi()
    {
        $this->assertTrue(
            \r\circle(\r\point(0.0, 0.0), 10.0, array('unit' => 'mi'))
                ->intersects(
                    \r\line(array(array(0.1, 0.0), array(2.0, 2.0)))
                )->run($this->conn)
        );
    }

    public function testTableGetIntersecting()
    {
        $res = $this->db()->table('geo')
            ->getIntersecting(
                \r\circle(\r\point(0.0, 0.0), 150.0, array('unit' => "km")),
                array('index' => 'geo')
            )->count()
            ->run($this->conn);

        $this->assertEquals(1.0, $res);
    }

    public function testTableGetIntersectingMgeo()
    {
        $res = $this->db()->table('geo')
            ->getIntersecting(
                \r\circle(\r\point(0.0, 0.0), 150.0, array('unit' => "km")),
                array('index' => 'mgeo')
            )->count()
            ->run($this->conn);

        $this->assertEquals(1.0, $res);
    }

    public function testTablegetNearest()
    {
        $res = $this->db()->table('geo')
            ->getNearest(
                \r\point(0.0, 0.0),
                array('max_dist' => 200.0, 'unit' => 'km', 'index' => 'geo')
            )->map(\r\row('dist')->coerceTo('STRING'))
            ->run($this->conn);

        $this->assertContains('111319490793273', str_replace('.', '', $res[0]));
        $this->assertContains('156899568291340', str_replace('.', '', $res[1]));
    }
}
