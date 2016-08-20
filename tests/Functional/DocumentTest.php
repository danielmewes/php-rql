<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

class DocumentTest extends TestCase
{
    public function setUp()
    {
        $this->conn = $this->getConnection();
        $this->data = $this->useDataset('Heroes');
        $this->data->populate();
    }

    public function tearDown()
    {
        $this->data->truncate();
    }

    public function testDocumentDefault()
    {
        $res = $this->db()->table('marvel')
            ->get('Iron Man')
            ->run($this->conn);

        $this->assertInstanceOf('ArrayObject', $res);
    }

    public function testDocumentArrayObject()
    {
        $res = $this->db()->table('marvel')
            ->get('Iron Man')
            ->run($this->conn, array('documentFormat' => 'ArrayObject'));

        $this->assertInstanceOf('ArrayObject', $res);
    }

    public function testDocumentNativeArray()
    {
        $res = $this->db()->table('marvel')
            ->get('Iron Man')
            ->run($this->conn, array('documentFormat' => 'array'));

        $this->assertInternalType('array', $res);
    }
}
