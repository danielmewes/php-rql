<?php


namespace r\Tests\Functional;

use r\Tests\TestCase;

// use function \r\js;

class ReplaceTest extends TestCase
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

    public function testReplace()
    {
        $res = $this->db()->table('marvel')
            ->get('Wolverine')
            ->replace(array('superhero' => 'Wolverine', 'age' => 30))
            ->run($this->conn);

        $this->assertObStatus(array('replaced' => 1), $res);
    }

    public function testReplaceNonAtomic()
    {
        $res = $this->db()->table('marvel')
            ->get('Wolverine')
            ->replace(array('superhero' => 'Wolverine', 'age' => \r\js('35')))
            ->run($this->conn, array('non_atomic' => true));

        $this->assertObStatus(array('replaced' => 1), $res);
    }
}
