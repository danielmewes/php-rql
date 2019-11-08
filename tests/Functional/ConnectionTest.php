<?php

namespace r\Tests\Functional;

use function r\expr;
use function r\js;
use r\Tests\TestCase;

class ConnectionTest extends TestCase
{
    protected function setUp(): void
    {
        $this->conn = $this->getConnection();
        $this->conn->reconnect();
    }

    public function testReconnect()
    {
        $res1 = expr(true)->run($this->conn);
        $this->conn->reconnect();
        $res2 = expr(true)->run($this->conn);
        $this->assertTrue($res1);
        $this->assertTrue($res2);
    }

    public function testTimeout()
    {
        $this->expectException('\r\Exceptions\RqlServerError');
        $this->expectExceptionMessage('Runtime error: JavaScript query `while(true) {}` timed out after 1.300 seconds');
        js('while(true) {}', 1.3)->run($this->conn);
    }

    public function testSetTimeout()
    {
        $this->conn->setTimeout(1);
        $this->expectException('\r\Exceptions\RqlServerError');
        $this->expectExceptionMessage('Runtime error: JavaScript query `while(true) {}` timed out after 2.000 seconds');
        js('while(true) {}', 2.0)->run($this->conn);
    }

    public function testSetTimeout60()
    {
        $this->conn->setTimeout(60);
        $this->expectException('\r\Exceptions\RqlServerError');
        $this->expectExceptionMessage('Runtime error: JavaScript query `while(true) {}` timed out after 2.000 seconds');
        js('while(true) {}', 2.0)->run($this->conn);
    }

    public function testNoReplyWait()
    {
        js('while(true) {}', 2.0)->run($this->conn, ['noreply' => true]);
        $t = microtime(true);
        $this->conn->noreplyWait();
        $this->assertGreaterThan(1.5, microtime(true) - $t);
    }

    public function testNoReplyReconnect()
    {
        js('while(true) {}', 2.0)->run($this->conn, ['noreply' => true]);
        $t = microtime(true);
        $this->conn->reconnect();
        $this->assertGreaterThan(1.5, microtime(true) - $t);
    }

    public function testNoReplyClose()
    {
        js('while(true) {}', 2.0)->run($this->conn, ['noreply' => true]);
        $t = microtime(true);
        $this->conn->close();
        $this->assertGreaterThan(1.5, microtime(true) - $t);
    }

    public function testNoReplyCloseImmediately()
    {
        js('while(true) {}', 2.0)->run($this->conn, ['noreply' => true]);
        $t = microtime(true);
        $this->conn->close(false);
        $this->assertLessThan(0.5, microtime(true) - $t);
    }

    public function testNoReplyReconnectImmediately()
    {
        js('while(true) {}', 2.0)->run($this->conn, ['noreply' => true]);
        $t = microtime(true);
        $this->conn->reconnect(false);
        $this->assertLessThan(0.5, microtime(true) - $t);
    }

    public function testServer()
    {
        $this->assertArrayHasKey('id', $this->conn->server());
        $this->assertArrayHasKey('name', $this->conn->server());
    }
}
