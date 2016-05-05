<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

// use function \r\expr;
// use function \r\js;

class ConnectionTest extends TestCase
{
    public function setUp()
    {
        $this->conn = $this->getConnection();
        $this->conn->reconnect();
    }

    public function testReconnect()
    {
        $res1 = \r\expr(true)->run($this->conn);
        $this->conn->reconnect();
        $res2 = \r\expr(true)->run($this->conn);

        $this->assertTrue($res1);
        $this->assertTrue($res2);
    }

    public function testTimeout()
    {
        $this->setExpectedException(
            '\r\Exceptions\RqlServerError',
            'Runtime error: JavaScript query `while(true) {}` timed out after 1.300 seconds'
        );

        \r\js('while(true) {}', 1.3)->run($this->conn);
    }

    public function testSetTimeout()
    {
        $this->conn->setTimeout(1);
        $this->setExpectedException(
            '\r\Exceptions\RqlServerError',
            'Runtime error: JavaScript query `while(true) {}` timed out after 2.000 seconds'
        );

        \r\js('while(true) {}', 2.0)->run($this->conn);
    }

    public function testSetTimeout60()
    {
        $this->conn->setTimeout(60);
        $this->setExpectedException(
            '\r\Exceptions\RqlServerError',
            'Runtime error: JavaScript query `while(true) {}` timed out after 2.000 seconds'
        );

        \r\js('while(true) {}', 2.0)->run($this->conn);
    }

    public function testNoReplyWait()
    {
        \r\js('while(true) {}', 2.0)->run($this->conn, array('noreply' => true));
        $t = microtime(true);
        $this->conn->noreplyWait();

        $this->assertGreaterThan(1.5, microtime(true) - $t);
    }

    public function testNoReplyReconnect()
    {
        \r\js('while(true) {}', 2.0)->run($this->conn, array('noreply' => true));
        $t = microtime(true);
        $this->conn->reconnect();

        $this->assertGreaterThan(1.5, microtime(true) - $t);
    }

    public function testNoReplyClose()
    {
        \r\js('while(true) {}', 2.0)->run($this->conn, array('noreply' => true));
        $t = microtime(true);
        $this->conn->close();

        $this->assertGreaterThan(1.5, microtime(true) - $t);
    }

    public function testNoReplyCloseImmediately()
    {
        \r\js('while(true) {}', 2.0)->run($this->conn, array('noreply' => true));
        $t = microtime(true);
        $this->conn->close(false);

        $this->assertLessThan(0.5, microtime(true) - $t);
    }

    public function testNoReplyReconnectImmediately()
    {
        \r\js('while(true) {}', 2.0)->run($this->conn, array('noreply' => true));
        $t = microtime(true);
        $this->conn->reconnect(false);

        $this->assertLessThan(0.5, microtime(true) - $t);
    }

    public function testServer()
    {
        $this->assertArrayHasKey("id", $this->conn->server());
        $this->assertArrayHasKey("name", $this->conn->server());
    }
}
