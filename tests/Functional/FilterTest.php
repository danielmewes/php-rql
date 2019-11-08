<?php

namespace r\Tests\Functional;

use function r\error;
use function r\expr;
use function r\row;
use r\Tests\TestCase;

class FilterTest extends TestCase
{
    protected function setUp(): void
    {
        $this->conn = $this->getConnection();
        $this->dataset = $this->useDataset('Heroes');
        $this->dataset->populate();
    }

    protected function tearDown(): void
    {
        $this->dataset->truncate();
    }

    public function testFilter()
    {
        $res = $this->db()->table('marvel')->filter(row('superhero')->eq('Iron Man'))->count()->run($this->conn);
        $this->assertEquals(1.0, $res);
    }

    public function testFilterFunction()
    {
        $res = $this->db()->table('marvel')->filter(function ($x) {
            return $x('superhero')->ne('Iron Man');
        })->count()->run($this->conn);
        $this->assertEquals(2.0, $res);
    }

    public function testFilterRowEq()
    {
        $res = $this->db()->table('marvel')->filter(row('foo')->eq('naaa'))->count()->run($this->conn);
        $this->assertEquals(0.0, $res);
    }

    public function testFilterError()
    {
        $this->expectException('\r\Exceptions\RqlServerError');
        $this->expectExceptionMessage('Runtime error: No attribute `foo` in object:');
        $res = $this->db()->table('marvel')->filter(row('foo')->eq('naaa'), error())->count()->run($this->conn);
    }

    public function testFilterErrorMsg()
    {
        $this->expectException('\r\Exceptions\RqlServerError');
        $this->expectExceptionMessage('msg');
        $res = $this->db()->table('marvel')->filter(row('foo')->eq('naaa'), error('msg'))->count()->run($this->conn);
    }

    public function testFilterMissing()
    {
        $res = $this->db()->table('marvel')->filter(row('foo')->eq('naaa'), true)->count()->run($this->conn);
        $this->assertEquals(3.0, $res);
    }

    public function testFilterMissingViaExpr()
    {
        $res = $this->db()->table('marvel')->filter(row('foo')->eq('naaa'), expr('true'))->count()->run($this->conn);
        $this->assertEquals(3.0, $res);
    }
}
