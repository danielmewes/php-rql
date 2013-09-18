<?php

class BetweenTest extends TestCase
{
    public function run()
    {
        $this->requireDataset('Heroes');
    
        r\db('Heroes')->table('marvel')->indexCreate('test', function($x) { return r\expr('5'); })->run($this->conn);
    
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->between('5', '5', 'test')->count(), 0.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->between('5', '5', array('index' => 'test'))->count(), 0.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->between('5', '5', array('index' => 'test', 'right_bound' => 'closed'))->count(), 3.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->between('A', 'Z')->count(), 3.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->between('I', 'J')->count(), 1.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->between('I', 'I')->count(), 0.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->between(null, 'J')->count(), 1.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->between('J', null)->count(), 2.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->between(null, null)->count(), 3.0);
        
        r\db('Heroes')->table('marvel')->indexDrop('test')->run($this->conn);
    }
}

?>
