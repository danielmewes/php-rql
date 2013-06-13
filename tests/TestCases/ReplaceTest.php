<?php

class ReplaceTest extends TestCase
{
    public function run()
    {
        $this->requireDataset('Heroes');
    
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->get('Wolverine')->replace(array( 'superhero' => 'Wolverine', 'age' => 30 )), array('unchanged' => 0, 'skipped' => 0, 'replaced' => 1, 'inserted' => 0, 'errors' => 0, 'deleted' => 0));
        
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->get('Wolverine')->replace(array( 'superhero' => 'Wolverine', 'age' => r\js('35') ), array('non_atomic' => true)), array('unchanged' => 0, 'skipped' => 0, 'replaced' => 1, 'inserted' => 0, 'errors' => 0, 'deleted' => 0));
        
        $this->datasets['Heroes']->reset();
    }
}

?>
