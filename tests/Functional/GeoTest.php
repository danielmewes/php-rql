<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;
use function \r\row;
use function \r\expr;
use function \r\line;
use function \r\point;
use function \r\circle;
use function \r\geojson;
use function \r\polygon;

class GeoTest extends TestCase
{
    protected function setUp(): void
    {
        $this->conn = $this->getConnection();
        $this->dataset = $this->useDataset('Geo');
        $this->dataset->populate();
    }

    protected function tearDown(): void
    {
        $this->dataset->truncate();
    }

    public function testGeoJson()
    {
        $res = geojson(['type' => 'Point', 'coordinates' => [0.0, 1.0]])->run($this->conn);
        $this->assertEquals(['$reql_type$' => 'GEOMETRY', 'type' => 'Point', 'coordinates' => [0.0, 1.0]], (array) $res);
    }

    public function testToGepJson()
    {
        $res = expr(['$reql_type$' => 'GEOMETRY', 'type' => 'Point', 'coordinates' => [0.0, 1.0]])->toGeojson()->run($this->conn);
        $this->assertEquals(['type' => 'Point', 'coordinates' => [0.0, 1.0]], (array) $res);
    }

    public function testPoint()
    {
        $res = point(0.0, 1.0)->run($this->conn);
        $this->assertEquals(['$reql_type$' => 'GEOMETRY', 'type' => 'Point', 'coordinates' => [0.0, 1.0]], (array) $res);
    }

    public function testLine()
    {
        $res = line([[0.0, 0.0], [0.0, 1.0], [1.0, 1.0]])->run($this->conn);
        $this->assertEquals(['$reql_type$' => 'GEOMETRY', 'type' => 'LineString', 'coordinates' => [[0.0, 0.0], [0.0, 1.0], [1.0, 1.0]]], (array) $res);
    }

    public function testLineFill()
    {
        $res = line([[0.0, 0.0], [0.0, 1.0], [1.0, 1.0]])->fill()->run($this->conn);
        $this->assertEquals(['$reql_type$' => 'GEOMETRY', 'type' => 'Polygon', 'coordinates' => [[[0.0, 0.0], [0.0, 1.0], [1.0, 1.0], [0.0, 0.0]]]], (array) $res);
    }

    public function testPolygon()
    {
        $res = polygon([[0.0, 0.0], [0.0, 1.0], [1.0, 1.0]])->run($this->conn);
        $this->assertEquals(['$reql_type$' => 'GEOMETRY', 'type' => 'Polygon', 'coordinates' => [[[0.0, 0.0], [0.0, 1.0], [1.0, 1.0], [0.0, 0.0]]]], (array) $res);
    }

    public function testPolygonSub()
    {
        $res = polygon([[0.0, 0.0], [0.0, 2.0], [2.0, 2.0], [2.0, 0.0]])->polygonSub(polygon([[0.5, 0.5], [0.5, 0.8], [0.8, 0.8]]))->run($this->conn);
        $this->assertEquals(['$reql_type$' => 'GEOMETRY', 'type' => 'Polygon', 'coordinates' => [[[0.0, 0.0], [0.0, 2.0], [2.0, 2.0], [2.0, 0.0], [0.0, 0.0]], [[0.5, 0.5], [0.5, 0.8], [0.8, 0.8], [0.5, 0.5]]]], (array) $res);
    }

    // These might fail due to rounding issues, depending on the server's architecture
    // removing the . might help
    public function testDistance()
    {
        $res = point(0.0, 1.0)->distance(point(1.0, 1.0))->coerceTo('STRING')->run($this->conn);
        $this->assertStringContainsString('1113026493394308', str_replace('.', '', $res));
    }

    public function testDistanceKm()
    {
        $res = point(0.0, 1.0)->distance(point(1.0, 1.0), ['unit' => 'km'])->coerceTo('STRING')->run($this->conn);
        $this->assertStringContainsString('1113026493394308', str_replace('.', '', $res));
    }

    public function testPolygonIntersects()
    {
        $this->assertTrue(polygon([[0.0, 0.0], [0.0, 1.0], [1.0, 1.0]])->intersects(line([[0.0, 0.0], [2.0, 2.0]]))->run($this->conn));
    }

    public function testPolygonIncludes()
    {
        $this->assertFalse(polygon([[0.0, 0.0], [0.0, 1.0], [1.0, 1.0]])->includes(line([[0.0, 0.0], [2.0, 2.0]]))->run($this->conn));
    }

    public function testCircle()
    {
        $this->assertFalse(circle(point(0.0, 0.0), 10.0)->intersects(line([[0.1, 0.0], [2.0, 2.0]]))->run($this->conn));
    }

    public function testCircleMi()
    {
        $this->assertTrue(circle(point(0.0, 0.0), 10.0, ['unit' => 'mi'])->intersects(line([[0.1, 0.0], [2.0, 2.0]]))->run($this->conn));
    }

    public function testTableGetIntersecting()
    {
        $res = $this->db()->table('geo')->getIntersecting(circle(point(0.0, 0.0), 150.0, ['unit' => 'km']), ['index' => 'geo'])->count()->run($this->conn);
        $this->assertEquals(1.0, $res);
    }

    public function testTableGetIntersectingMgeo()
    {
        $res = $this->db()->table('geo')->getIntersecting(circle(point(0.0, 0.0), 150.0, ['unit' => 'km']), ['index' => 'mgeo'])->count()->run($this->conn);
        $this->assertEquals(1.0, $res);
    }

    public function testTablegetNearest()
    {
        $res = $this->db()->table('geo')->getNearest(point(0.0, 0.0), ['max_dist' => 200.0, 'unit' => 'km', 'index' => 'geo'])->map(row('dist')->coerceTo('STRING'))->run($this->conn);
        $this->assertStringContainsString('111319490793273', str_replace('.', '', $res[0]));
        $this->assertStringContainsString('156899568291340', str_replace('.', '', $res[1]));
    }
}
