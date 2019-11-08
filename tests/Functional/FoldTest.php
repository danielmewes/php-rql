<?php

namespace r\Tests\Functional;

use function r\expr;
use r\Tests\TestCase;

class FoldTest extends TestCase
{
    public function testFoldReduction()
    {
        $this->assertEquals(15.0, expr([1, 2, 3, 4])->fold(5, function ($acc, $v) {
            return $acc->add($v);
        })->run($this->conn));
    }

    public function testFoldEmit()
    {
        $this->assertEquals([5, 6, 8, 11, 15], expr([1, 2, 3, 4])->fold(5, function ($acc, $v) {
            return $acc->add($v);
        }, ['emit' => function ($o, $c, $n) {
            return [$o];
        }, 'final_emit' => function ($a) {
            return [$a];
        }])->run($this->conn));
    }
}
