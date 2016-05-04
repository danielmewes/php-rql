<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

// use function \r\binary;

class BinaryTest extends TestCase
{
    public function testBinary()
    {
        $this->assertEquals('abcdefg', \r\binary('abcdefg')->run($this->conn));
    }

    public function testBinaryNull()
    {
        $this->assertEquals('abcdefg\0\0foo', \r\binary('abcdefg\0\0foo')->run($this->conn));
    }

    public function testBinaryNative()
    {
        $this->assertEquals(
            'abcdefg',
            \r\binary('abcdefg')->run($this->conn, array('binaryFormat' => 'native'))
        );
    }

    public function testBinaryRaw()
    {
        $this->assertEquals(
            array('$reql_type$' => 'BINARY', 'data' => 'YWJjZGVmZw=='),
            (array)\r\binary('abcdefg')->run($this->conn, array('binaryFormat' => 'raw'))
        );
    }
}
