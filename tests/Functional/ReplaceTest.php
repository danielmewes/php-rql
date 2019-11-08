<?php

namespace r\Tests\Functional;

use function r\js;
use r\Tests\TestCase;

class ReplaceTest extends TestCase
{
    protected function setUp(): void
    {
        $this->conn = $this->getConnection();
        $this->dataset = $this->useDataset('Heroes');
        $this->dataset->populate();
    }

    protected function tearDown(): void
    {
        $this->dataset->truncate();
    }

    public function testReplace()
    {
        $res = $this->db()->table('marvel')
            ->get('Wolverine')
            ->replace(['superhero' => 'Wolverine', 'age' => 30])
            ->run($this->conn);

        $this->assertObStatus(['replaced' => 1], $res);
    }

    public function testReplaceNonAtomic()
    {
        $res = $this->db()->table('marvel')
            ->get('Wolverine')
            ->replace(['superhero' => 'Wolverine', 'age' => js('35')])
            ->run($this->conn, ['non_atomic' => true]);

        $this->assertObStatus(['replaced' => 1], $res);
    }
}
