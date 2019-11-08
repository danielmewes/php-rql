<?php

namespace r\Tests\Functional;

use function r\js;
use r\Tests\TestCase;

class JsTest extends TestCase
{
    public function testJs()
    {
        $this->assertEquals('str1str2', js("'str1' + 'str2'")->run($this->conn));
    }

    public function testJsTimeout()
    {
        $this->expectException('\r\Exceptions\RqlServerError');
        $this->expectExceptionMessage('Runtime error: JavaScript query `while(true) {}` timed out after 1.300 seconds');
        js('while(true) {}', 1.3)->run($this->conn);
    }
}
