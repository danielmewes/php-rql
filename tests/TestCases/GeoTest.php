<?php

class GeoTest extends TestCase
{
    public function run()
    {
        $this->checkQueryResult(r\geojson(array('type' => 'Point', 'coordinates' => array(0.0, 1.0))),
            array('$reql_type$' => 'GEOMETRY', 'type' => 'Point', 'coordinates' => array(0.0, 1.0)));
        $this->checkQueryResult(r\expr(array('$reql_type$' => 'GEOMETRY', 'type' => 'Point', 'coordinates' => array(0.0, 1.0)))->toGeojson(),
            array('type' => 'Point', 'coordinates' => array(0.0, 1.0)));
        $this->checkQueryResult(r\point(0.0, 1.0),
            array('$reql_type$' => 'GEOMETRY', 'type' => 'Point', 'coordinates' => array(0.0, 1.0)));
        $this->checkQueryResult(r\line(array(array(0.0, 0.0), array(0.0, 1.0), array(1.0, 1.0))),
            array('$reql_type$' => 'GEOMETRY', 'type' => 'LineString', 'coordinates' => array(array(0.0, 0.0), array(0.0, 1.0), array(1.0, 1.0))));
        $this->checkQueryResult(r\line(array(array(0.0, 0.0), array(0.0, 1.0), array(1.0, 1.0)))->fill(),
            array('$reql_type$' => 'GEOMETRY', 'type' => 'Polygon', 'coordinates' => array(array(array(0.0, 0.0), array(0.0, 1.0), array(1.0, 1.0), array(0.0, 0.0)))));
        $this->checkQueryResult(r\polygon(array(array(0.0, 0.0), array(0.0, 1.0), array(1.0, 1.0))),
            array('$reql_type$' => 'GEOMETRY', 'type' => 'Polygon', 'coordinates' => array(array(array(0.0, 0.0), array(0.0, 1.0), array(1.0, 1.0), array(0.0, 0.0)))));
        $this->checkQueryResult(r\polygon(array(array(0.0, 0.0), array(0.0, 2.0), array(2.0, 2.0), array(2.0, 0.0)))->polygonSub(r\polygon(array(array(0.5, 0.5), array(0.5, 0.8), array(0.8, 0.8)))),
            array('$reql_type$' => 'GEOMETRY', 'type' => 'Polygon', 'coordinates' => array(array(array(0.0, 0.0), array(0.0, 2.0), array(2.0, 2.0), array(2.0, 0.0), array(0.0, 0.0)), array(array(0.5, 0.5), array(0.5, 0.8), array(0.8, 0.8), array(0.5, 0.5)))));

        // These might fail due to rounding issues, depending on the server's architecture
        $this->checkQueryResult(r\point(0.0, 1.0)->distance(r\point(1.0, 1.0))->coerceTo("STRING"),
            "111302.64933943082");
        $this->checkQueryResult(r\point(0.0, 1.0)->distance(r\point(1.0, 1.0), array("unit" => "km"))->coerceTo("STRING"),
            "111.30264933943083");

        $this->checkQueryResult(r\polygon(array(array(0.0, 0.0), array(0.0, 1.0), array(1.0, 1.0)))->intersects(r\line(array(array(0.0, 0.0), array(2.0, 2.0)))),
            true);
        $this->checkQueryResult(r\polygon(array(array(0.0, 0.0), array(0.0, 1.0), array(1.0, 1.0)))->includes(r\line(array(array(0.0, 0.0), array(2.0, 2.0)))),
            false);

        $this->checkQueryResult(r\circle(r\point(0.0, 0.0), 10.0)->intersects(r\line(array(array(0.1, 0.0), array(2.0, 2.0)))),
            false);
        $this->checkQueryResult(r\circle(r\point(0.0, 0.0), 10.0, array("unit" => "mi"))->intersects(r\line(array(array(0.1, 0.0), array(2.0, 2.0)))),
            true);

        $this->requireDataset('Geo');
        $this->checkQueryResult(r\db('Geo')->table('geo')->getIntersecting(r\circle(r\point(0.0, 0.0), 150.0, array('unit' => "km")), array('index' => 'geo'))->count(),
            1.0);
        $this->checkQueryResult(r\db('Geo')->table('geo')->getIntersecting(r\circle(r\point(0.0, 0.0), 150.0, array('unit' => "km")), array('index' => 'mgeo'))->count(),
            1.0);
        // Again, there might be rounding issues on some servers
        $this->checkQueryResult(r\db('Geo')->table('geo')->getNearest(r\point(0.0, 0.0), array('max_dist' => 200.0, 'unit' => 'km', 'index' => 'geo'))->map(r\row('dist')->coerceTo('STRING')),
            array('111.3194907932735731', '156.89956829134027316'));
    }
}

?>
