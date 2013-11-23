<?php

class IndexTest extends TestCase
{
    public function run()
    {
        $this->requireDataset('Heroes');
    
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexList()->count(), 0.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexCreate('superpower'), array('created' => 1.0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexCreate('foo', function ($r) { return r\expr(5); } ), array('created' => 1.0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexCreateMulti('superpower_m'), array('created' => 1.0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexCreateMulti('foo_m', function ($r) { return r\expr(array(5, 6)); }), array('created' => 1.0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexList()->count(), 4.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexDrop('superpower'), array('dropped' => 1.0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexDrop('foo'), array('dropped' => 1.0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexDrop('superpower_m'), array('dropped' => 1.0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexDrop('foo_m'), array('dropped' => 1.0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->indexList()->count(), 0.0);
        
        $this->datasets['Heroes']->reset();
    }
}

?>
