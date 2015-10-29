<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

// use function \r\expr;

class ProfilingTest extends TestCase
{
    public function testProfile()
    {
        $res = \r\expr(1)->profile($this->conn);

        $this->assertEquals('Evaluating datum.', $res[0]['description']);

    }

    public function testProfileNoOpts()
    {
        $res = \r\expr(1)->profile($this->conn, null, $status);

        $this->assertEquals('Evaluating datum.', $res[0]['description']);
        $this->assertEquals(1, $status);

    }
}
