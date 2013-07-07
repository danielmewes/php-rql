<?php

class GetTest extends TestCase
{
    public function run()
    {
        $this->requireDataset('Heroes');
    
        r\db('Heroes')->table('marvel')->indexCreate('test', function($x) { return r\expr('5'); })->run($this->conn);
    
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->getAll('5', 'test')->count(), 3.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->getAll('Iron Man')->count(), 1.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->getMultiple(array('Iron Man', 'Wolverine'))->count(), 2.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->get('Iron Man'), array('superhero' => 'Iron Man', 'superpower' => 'Arc Reactor', 'combatPower' => 2.0, 'compassionPower' => 1.5 ));
        
        r\db('Heroes')->table('marvel')->indexDrop('test')->run($this->conn);
    }
}

?>
