<?php

class JoinTest extends TestCase
{
    public function run()
    {
        $this->requireDataset('Joins');
    
        $this->checkQueryResult(r\db('Joins')->table('t1')->innerJoin(r\db('Joins')->table('t2'), function ($r1, $r2) { return $r1('other')->eq($r2('id')); } ),
            array(
                array('left' => array('id' => 1, 'other' => 'a'), 'right' => array('id' => 'a', 'other' => 1 )),
                array('left' => array('id' => 2, 'other' => 'a'), 'right' => array('id' => 'a', 'other' => 1 )),
                array('left' => array('id' => 3, 'other' => 'b'), 'right' => array('id' => 'b', 'other' => 1 ))
            ));
        $this->checkQueryResult(r\db('Joins')->table('t1')->outerJoin(r\db('Joins')->table('t2'), function ($r1, $r2) { return $r1('other')->eq($r2('id')); } ),
            array(
                array('left' => array('id' => 1, 'other' => 'a'), 'right' => array('id' => 'a', 'other' => 1 )),
                array('left' => array('id' => 2, 'other' => 'a'), 'right' => array('id' => 'a', 'other' => 1 )),
                array('left' => array('id' => 3, 'other' => 'b'), 'right' => array('id' => 'b', 'other' => 1 ))
            ));
            
        $this->checkQueryResult(r\db('Joins')->table('t2')->innerJoin(r\db('Joins')->table('t1'), function ($r1, $r2) { return $r1('other')->eq($r2('id')); } ),
            array(
                array('right' => array('id' => 1, 'other' => 'a'), 'left' => array('id' => 'a', 'other' => 1 )),
                array('right' => array('id' => 1, 'other' => 'a'), 'left' => array('id' => 'b', 'other' => 1 ))
            ));
        $this->checkQueryResult(r\db('Joins')->table('t2')->outerJoin(r\db('Joins')->table('t1'), function ($r1, $r2) { return $r1('other')->eq($r2('id')); } ),
            array(
                array('right' => array('id' => 1, 'other' => 'a'), 'left' => array('id' => 'a', 'other' => 1 )),
                array('right' => array('id' => 1, 'other' => 'a'), 'left' => array('id' => 'b', 'other' => 1 )),
                array('left' => array('id' => 'c', 'other' => 5 ))
            ));
            
        $this->checkQueryResult(r\db('Joins')->table('t1')->eqJoin('other', r\db('Joins')->table('t2')),
            array(
                array('left' => array('id' => 1, 'other' => 'a'), 'right' => array('id' => 'a', 'other' => 1 )),
                array('left' => array('id' => 2, 'other' => 'a'), 'right' => array('id' => 'a', 'other' => 1 )),
                array('left' => array('id' => 3, 'other' => 'b'), 'right' => array('id' => 'b', 'other' => 1 ))
            ));
        $this->checkQueryResult(r\db('Joins')->table('t1')->eqJoin('id', r\db('Joins')->table('t2'), 'other'),
            array(
                array('left' => array('id' => 1, 'other' => 'a'), 'right' => array('id' => 'a', 'other' => 1 )),
                array('left' => array('id' => 1, 'other' => 'a'), 'right' => array('id' => 'b', 'other' => 1 ))
            ));
            
        $this->checkQueryResult(r\db('Joins')->table('t1')->eqJoin('id', r\db('Joins')->table('t2'), 'other')->zip(),
            array(
                array('id' => 'a', 'other' => 1 ),
                array('id' => 'b', 'other' => 1 )
            ));
    }
}

?>
