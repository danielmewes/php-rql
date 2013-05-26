<?php

class ReplaceTest extends TestCase
{
    public function run()
    {
        $this->requireDataset('Heroes');
    
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->get('superman')->replace(array( 'superhero' => 'superman', 'age' => 30 )), array('unchanged' => 0, 'skipped' => 0, 'replaced' => 1, 'inserted' => 0, 'errors' => 0, 'deleted' => 0));
        
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->get('superman')->replace(array( 'superhero' => 'superman', 'age' => r\js('35') ), true), array('unchanged' => 0, 'skipped' => 0, 'replaced' => 1, 'inserted' => 0, 'errors' => 0, 'deleted' => 0));
        
        $this->datasets['Heroes']->reset();
    }
}

?>
