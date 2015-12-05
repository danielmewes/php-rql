<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

// use function \r\expr;

class UuidTest extends TestCase
{
    public function testRandom()
    {
        $this->assertEquals("STRING", \r\uuid()->typeOf()->run($this->conn));
    }

    public function testDeterministic()
    {
        $this->assertEquals(
            "f05614a4-c046-5cb1-ac4f-73fe8230477e",
            \r\uuid("test")->run($this->conn)
        );
    }

    public function testTerm()
    {
        $this->assertEquals(
            "f05614a4-c046-5cb1-ac4f-73fe8230477e",
            \r\uuid(\r\expr("test"))->run($this->conn)
        );
    }
}
