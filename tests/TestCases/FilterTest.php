<?php

class FilterTest extends TestCase
{
    public function run()
    {    
        $this->requireDataset('Heroes');
    
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->filter(r\row('superhero')->eq('Iron Man'))->count(), 1.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->filter(function ($x) { return $x('superhero')->ne('Iron Man'); })->count(), 2.0);
        
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->filter(r\row('foo')->eq('naaa'))->count(), 0.0);
        $caught = false;
        try {
            $this->checkQueryResult(r\db('Heroes')->table('marvel')->filter(r\row('foo')->eq('naaa'), r\error())->count(), 0.0);
        } catch (r\RqlUserError $e) {
            $caught = true;
        }
        if (!$caught) echo "Filter with default r\error() did not throw.";
        $caught = false;
        try {
            $this->checkQueryResult(r\db('Heroes')->table('marvel')->filter(r\row('foo')->eq('naaa'), r\error('msg'))->count(), 0.0);
        } catch (r\RqlUserError $e) {
            $caught = true;
        }
        if (!$caught) echo "Filter with default r\error() did not throw.";
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->filter(r\row('foo')->eq('naaa'), true)->count(), 3.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->filter(r\row('foo')->eq('naaa'), r\expr('true'))->count(), 3.0);
    }
}

?>
