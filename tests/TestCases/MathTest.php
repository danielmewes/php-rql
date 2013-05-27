<?php

class MathTest extends TestCase
{
    public function run()
    {   
        $this->checkQueryResult(r\expr('a')->add('b'),
            'ab'); 
        $this->checkQueryResult(r\expr(1)->add(2),
            3.0);
        $this->checkQueryResult(r\expr(1)->sub(2),
            -1.0);
        $this->checkQueryResult(r\expr(1)->mul(2),
            2.0);
        $this->checkQueryResult(r\expr(1)->div(2),
            0.5);
        $this->checkQueryResult(r\expr(1)->mod(2),
            1.0);
            
        $this->checkQueryResult(r\expr(true)->rAnd(true),
            true);
        $this->checkQueryResult(r\expr(true)->rAnd(false),
            false);
        $this->checkQueryResult(r\expr(false)->rAnd(true),
            false);
        $this->checkQueryResult(r\expr(false)->rAnd(false),
            false);
            
        $this->checkQueryResult(r\expr(true)->rOr(true),
            true);
        $this->checkQueryResult(r\expr(true)->rOr(false),
            true);
        $this->checkQueryResult(r\expr(false)->rOr(true),
            true);
        $this->checkQueryResult(r\expr(false)->rOr(false),
            false);
            
        $this->checkQueryResult(r\expr(1.0)->eq(1.0),
            true);
        $this->checkQueryResult(r\expr(1.0)->eq(-1.0),
            false);
            
        $this->checkQueryResult(r\expr(1.0)->ne(1.0),
            false);
        $this->checkQueryResult(r\expr(1.0)->ne(-1.0),
            true);
            
        $this->checkQueryResult(r\expr(1.0)->gt(1.0),
            false);
        $this->checkQueryResult(r\expr(1.0)->gt(-1.0),
            true);
            
        $this->checkQueryResult(r\expr(1.0)->ge(1.0),
            true);
        $this->checkQueryResult(r\expr(1.0)->ge(-1.0),
            true);
            
        $this->checkQueryResult(r\expr(1.0)->lt(1.0),
            false);
        $this->checkQueryResult(r\expr(1.0)->lt(-1.0),
            false);
            
        $this->checkQueryResult(r\expr(1.0)->le(1.0),
            true);
        $this->checkQueryResult(r\expr(1.0)->le(-1.0),
            false);
            
        $this->checkQueryResult(r\expr(true)->not(),
            false);
        $this->checkQueryResult(r\expr(false)->not(),
            true);
    }
}

?>
