<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;
use r\Exceptions\RqlServerError;

//use function \r\expr;

class IndexTest extends TestCase
{
    public function setUp()
    {
        $this->conn = $this->getConnection();
        $this->data = $this->useDataset('Heroes');
        $this->data->populate();

        $this->index = 'index_' . time() . rand(999, 9999);
        $this->index2 = 'index_' . time() . rand(999, 9999);
    }

    public function tearDown()
    {
        try {
            $this->db()->table('marvel')->indexDrop($this->index)->run($this->conn);
        } catch (RqlServerError $e) {
            // were trying to remove indexes that may not have been created
            // no need to worry about those
            $msg = 'Runtime error: Index `' . $this->index . '` does not exist';
            if (strpos($e->getMessage(), $msg) !== 0) {
                throw $e;
            }
        }

        try {
            $this->db()->table('marvel')->indexDrop($this->index2)->run($this->conn);
        } catch (RqlServerError $e) {
            // were trying to remove indexes that may not have been created
            // no need to worry about those
            $msg = 'Runtime error: Index `' . $this->index2 . '` does not exist';
            if (strpos($e->getMessage(), $msg) !== 0) {
                throw $e;
            }
        }

        $this->data->truncate();
    }

    public function testListIndex()
    {
        $this->assertEquals(
            0.0,
            $this->db()->table('marvel')->indexList()->count()->run($this->conn)
        );
    }

    public function testCreateIndex()
    {
        $this->assertEquals(
            array('created' => 1.0),
            (array)$this->db()->table('marvel')->indexCreate($this->index)->run($this->conn)
        );
    }

    public function testCreateIndexFunction()
    {
        $this->assertEquals(
            array('created' => 1.0),
            (array)$this->db()->table('marvel')
                ->indexCreate($this->index, function ($r) {
                    return \r\expr(5);
                })
                ->run($this->conn)
        );
    }

    public function testCreateMulti()
    {
        $this->assertEquals(
            array('created' => 1.0),
            (array)$this->db()->table('marvel')
                ->indexCreateMulti($this->index)
                ->run($this->conn)
        );
    }

    public function testCreateMultiFunction()
    {
        $this->assertEquals(
            array('created' => 1.0),
            (array)$this->db()->table('marvel')
                ->indexCreateMulti($this->index, function ($r) {
                    return \r\expr(array(5, 6));
                })
                ->run($this->conn)
        );
    }

    public function testCreateCount()
    {
        $this->db()->table('marvel')->indexCreate($this->index)->run($this->conn);
        $this->assertEquals(
            1.0,
            $this->db()->table('marvel')->indexList()->count()->run($this->conn)
        );
    }

    public function testIndexWait()
    {
        $this->db()->table('marvel')->indexCreate($this->index)->run($this->conn);

        $res = $this->db()->table('marvel')
            ->indexWait($this->index)
            ->pluck(array('index', 'ready'))
            ->run($this->conn);

        $res = $this->toArray($res);

        $this->assertEquals(array(array('index' => $this->index, 'ready' => true)), $res);
    }

    public function testIndexStatus()
    {
        $res = $this->db()->table('marvel')->indexCreate($this->index)->run($this->conn);

        $res = $this->db()->table('marvel')
            ->indexStatus($this->index)
            ->pluck(array('index', 'ready'))
            ->run($this->conn);

        $res = $this->toArray($res);

        $this->assertEquals(array(array('index' => $this->index, 'ready' => false)), $res);
    }

    /*
     * results aren't always returned in the order we excpect them
     * so in the next few tests, were going to sort excpected and res to
     * ensure their order
     */

    public function testIndexWaitMultiple()
    {
        $excpected = array(
            array('index' => $this->index, 'ready' => true),
            array('index' => $this->index2, 'ready' => true)
        );
        usort($excpected, function ($a, $b) {
            return strcmp($a['index'], $b['index']);
        });

        $this->db()->table('marvel')->indexCreate($this->index)->run($this->conn);
        $this->db()->table('marvel')->indexCreate($this->index2)->run($this->conn);

        $res = $this->db()->table('marvel')
            ->indexWait(array($this->index, $this->index2))
            ->pluck(array('index', 'ready'))
            ->run($this->conn);

        $res = $this->toArray($res);
        usort($res, function ($a, $b) {
            return strcmp($a['index'], $b['index']);
        });
        $this->assertEquals($excpected, $res);
    }

    public function testIndexStatusMultiple()
    {
        $excpected = array(
            array('index' => $this->index, 'ready' => false),
            array('index' => $this->index2, 'ready' => false)
        );
        usort($excpected, function ($a, $b) {
            return strcmp($a['index'], $b['index']);
        });

        $this->db()->table('marvel')->indexCreate($this->index)->run($this->conn);
        $this->db()->table('marvel')->indexCreate($this->index2)->run($this->conn);

        $res = $this->db()->table('marvel')
            ->indexStatus()
            ->pluck(array('index', 'ready'))
            ->run($this->conn);

        $res = $this->toArray($res);
        usort($res, function ($a, $b) {
            return strcmp($a['index'], $b['index']);
        });
        $this->assertEquals($excpected, $res);
    }

    public function testDropIndex()
    {
        $this->db()->table('marvel')->indexCreate($this->index)->run($this->conn);

        $this->assertEquals(
            array('dropped' => 1.0),
            (array)$this->db()->table('marvel')->indexDrop($this->index)->run($this->conn)
        );
    }
}
