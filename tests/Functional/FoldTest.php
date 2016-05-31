<?php

namespace r\Tests\Functional;

use r\Tests\TestCase;

class FoldTest extends TestCase
{
    public function testFoldReduction()
    {
        $this->assertEquals(
            15.0,
            \r\expr(array(1, 2, 3, 4))
                ->fold(5, function ($acc, $v) {
                    return $acc->add($v);
                })
                ->run($this->conn)
        );
    }

    public function testFoldEmit()
    {
        $this->assertEquals(
            array(5, 6, 8, 11, 15),
            \r\expr(array(1, 2, 3, 4))
                ->fold(5, function ($acc, $v) {
                    return $acc->add($v);
                },
                array(
                    'emit' => function($o, $c, $n) { return array($o); },
                    'final_emit' => function($a) { return array($a); }
                ))
                ->run($this->conn)
        );
    }
}
