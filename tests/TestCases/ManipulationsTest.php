<?php

class ManipulationsTest extends TestCase
{
    public function run()
    {    
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => 2)))->filter(r\row()->attr('y')->eq(2))->pluck('x'),
            array(array('x' => 1)));
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => 2)))->filter(r\row('y')->eq(2))->pluck('x'),
            array(array('x' => 1)));
        
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => 2)))->pluck('x'),
            array(array('x' => 1)));
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => 2)))->pluck(array('x', 'y')),
            array(array('x' => 1, 'y' => 2)));
            
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => 2)))->without('x'),
            array(array('y' => 2)));
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => 2)))->without(array('x', 'y')),
            array(array()));
        
        $this->checkQueryResult(r\expr(array('x' => 1))->merge(array('y' => 2)),
            array('x' => 1, 'y' => 2));
        $this->checkQueryResult(r\expr(array('x' => 1))->merge(r\expr(array('y' => 2))),
            array('x' => 1, 'y' => 2));
            
        $this->checkQueryResult(r\expr(array(1, 2, 3))->append(4),
            array(1, 2, 3, 4));
        $this->checkQueryResult(r\expr(array(1, 2, 3))->append(r\expr(4)),
            array(1, 2, 3, 4));
            
        $this->checkQueryResult(r\expr(array('x' => 1, 'y' => 2))->contains('x'),
            true);
        $this->checkQueryResult(r\expr(array('x' => 1, 'y' => 2))->contains('foo'),
            false);
    }
}

?>
