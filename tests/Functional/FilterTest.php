<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

// use function \r\row;
// use function \r\error;
// use function \r\expr;

class FilterTest extends TestCase
{
    public function setUp(): void
    {
        $this->conn = $this->getConnection();
        $this->data = $this->useDataset('Heroes');
        $this->data->populate();
    }

    public function tearDown(): void
    {
        $this->data->truncate();
    }

    public function testFilter()
    {
        $res = $this->db()->table('marvel')
            ->filter(\r\row('superhero')->eq('Iron Man'))
            ->count()
            ->run($this->conn);

        $this->assertEquals(1.0, $res);
    }

    public function testFilterFunction()
    {
        $res = $this->db()->table('marvel')
            ->filter(function ($x) {
                return $x('superhero')->ne('Iron Man');
            })
            ->count()
            ->run($this->conn);

        $this->assertEquals(2.0, $res);
    }

    public function testFilterRowEq()
    {
        $res = $this->db()->table('marvel')
            ->filter(\r\row('foo')->eq('naaa'))
            ->count()
            ->run($this->conn);

        $this->assertEquals(0.0, $res);
    }

    public function testFilterError()
    {
        $this->expectException(\r\Exceptions\RqlServerError::class);
        $this->expectExceptionMessage(
            'Runtime error: No attribute `foo` in object:'
        );

        $res = $this->db()->table('marvel')
            ->filter(\r\row('foo')->eq('naaa'), \r\error())
            ->count()
            ->run($this->conn);
    }

    public function testFilterErrorMsg()
    {
        $this->expectException(\r\Exceptions\RqlServerError::class);
        $this->expectExceptionMessage(
            'msg'
        );

        $res = $this->db()->table('marvel')
            ->filter(\r\row('foo')->eq('naaa'), \r\error('msg'))
            ->count()
            ->run($this->conn);
    }

    public function testFilterMissing()
    {
        $res = $this->db()->table('marvel')
            ->filter(\r\row('foo')->eq('naaa'), true)
            ->count()
            ->run($this->conn);

        $this->assertEquals(3.0, $res);
    }

    public function testFilterMissingViaExpr()
    {
        $res = $this->db()->table('marvel')
            ->filter(\r\row('foo')->eq('naaa'), \r\expr('true'))
            ->count()
            ->run($this->conn);

        $this->assertEquals(3.0, $res);
    }
}
