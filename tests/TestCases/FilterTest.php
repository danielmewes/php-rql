<?php

class FilterTest extends TestCase
{
    public function run()
    {    
        $this->requireDataset('Heroes');
    
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->filter(r\row('superhero')->eq('Iron Man'))->count(), 1.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->filter(function ($x) { return $x('superhero')->ne('Iron Man'); })->count(), 2.0);
    }
}

?>
