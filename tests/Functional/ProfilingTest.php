<?php

namespace r\Tests\Functional;

use function r\expr;
use r\Tests\TestCase;

class ProfilingTest extends TestCase
{
    public function testProfile()
    {
        $res = expr(1)->profile($this->conn);
        $this->assertEquals('Evaluating datum.', $res[0]['description']);
    }

    public function testProfileNoOpts()
    {
        $res = expr(1)->profile($this->conn, null, $status);
        $this->assertEquals('Evaluating datum.', $res[0]['description']);
        $this->assertEquals(1, $status);
    }
}
