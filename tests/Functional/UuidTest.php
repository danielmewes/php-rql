<?php

namespace r\Tests\Functional;

use function r\expr;
use function r\uuid;
use r\Tests\TestCase;

class UuidTest extends TestCase
{
    public function testRandom()
    {
        $this->assertEquals('STRING', uuid()->typeOf()->run($this->conn));
    }

    public function testDeterministic()
    {
        $this->assertEquals('f05614a4-c046-5cb1-ac4f-73fe8230477e', uuid('test')->run($this->conn));
    }

    public function testTerm()
    {
        $this->assertEquals('f05614a4-c046-5cb1-ac4f-73fe8230477e', uuid(expr('test'))->run($this->conn));
    }
}
