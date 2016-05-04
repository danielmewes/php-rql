<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

// use function \r\js;

class JsTest extends TestCase
{
    public function testJs()
    {
        $this->assertEquals('str1str2', \r\js("'str1' + 'str2'")->run($this->conn));
    }

    public function testJsTimeout()
    {
        $this->setExpectedException(
            '\r\Exceptions\RqlServerError',
            'Runtime error: JavaScript query `while(true) {}` timed out after 1.300 seconds'
        );

        \r\js('while(true) {}', 1.3)->run($this->conn);
    }
}
