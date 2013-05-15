<?php

class IndexTest extends TestCase
{
    public function run()
    {
        $this->requireDataset('Heroes');
    
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexList()->count(), 0.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexCreate('superpower'), array('created' => 1.0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexCreate('foo', function ($r) { return r\expr(5); } ), array('created' => 1.0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexList()->count(), 2.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexDrop('superpower'), array('dropped' => 1.0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexDrop('foo'), array('dropped' => 1.0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexList()->count(), 0.0);
        
        $this->datasets['Heroes']->reset();
    }
}

?>
