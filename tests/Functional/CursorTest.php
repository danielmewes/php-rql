<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

class CursorTest extends TestCase
{
    public function setUp()
    {
        $this->conn = $this->getConnection();
        $this->data = $this->useDataset('Huge');
        $this->data->populate();

        $this->doc = array('key' => str_repeat('var', 1000));
        $this->docs = array_fill(0, 5000, $this->doc);
    }

    public function tearDown()
    {
        $this->data->truncate();
    }

    public function testCursor()
    {
        $cursor = $this->db()->table('t5000')->without('id')->run($this->conn);

        $i = 0;
        foreach ($cursor as $val) {
            $this->assertEquals($this->doc, (array)$val);
            if ($i++ >= 100) {
                break;
            }
        }
        unset($cursor); // not sure what this is doing...
    }

    public function testCursorClose()
    {
        $cursor = $this->db()->table('t5000')->without('id')->run($this->conn);
        $cursor->close();

        $this->assertCount(0, $cursor->toArray());
    }

    public function testLargeGet()
    {
        $cursor = $this->db()->table('t5000')->without('id')->run($this->conn);

        $res = array_map(function ($item) {
            return (array) $item;
        }, $cursor->toArray());
        $this->assertEquals($this->docs, $res);
    }
}
