<?php

class TableTest extends TestCase
{
    public function run()
    {
        $this->requireDataset('Heroes');
    
        $testResult = r\db('Heroes')->table('marvel')->orderBy('superhero')->run($this->conn)->toNative();
        $this->checkQueryResult(r\expr($testResult)->count(), 3.0);
        
        $this->datasets['Heroes']->reset();
    }
}

?>
