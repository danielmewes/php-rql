<?php

namespace r\Tests\Functional;

use function r\binary;
use r\Tests\TestCase;

class BinaryTest extends TestCase
{
    public function testBinary()
    {
        $this->assertEquals('abcdefg', binary('abcdefg')->run($this->conn));
    }

    public function testBinaryNull()
    {
        $this->assertEquals('abcdefg\0\0foo', binary('abcdefg\0\0foo')->run($this->conn));
    }

    public function testBinaryNative()
    {
        $this->assertEquals('abcdefg', binary('abcdefg')->run($this->conn, ['binaryFormat' => 'native']));
    }

    public function testBinaryRaw()
    {
        $this->assertEquals(['$reql_type$' => 'BINARY', 'data' => 'YWJjZGVmZw=='], (array) binary('abcdefg')->run($this->conn, ['binaryFormat' => 'raw']));
    }
}
