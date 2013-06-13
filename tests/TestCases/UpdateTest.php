<?php

class UpdateTest extends TestCase
{
    public function run()
    {
        $this->requireDataset('Heroes');
    
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->get('Wolverine')->update( array('age' => 30) ), array('unchanged' => 0, 'skipped' => 0, 'replaced' => 1, 'inserted' => 0, 'errors' => 0, 'deleted' => 0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->update( function ($r) { return $r->merge(array('age' => 5)); } ), array('unchanged' => 0, 'skipped' => 0, 'replaced' => 3, 'inserted' => 0, 'errors' => 0, 'deleted' => 0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->update(array( 'age' => r\row('age')->add(1) )), array('unchanged' => 0, 'skipped' => 0, 'replaced' => 3, 'inserted' => 0, 'errors' => 0, 'deleted' => 0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->update(array( 'age' => r\row('age')->add(r\js('1')) ), array('durability' => 'soft', 'non_atomic' => true)), array('unchanged' => 0, 'skipped' => 0, 'replaced' => 3, 'inserted' => 0, 'errors' => 0, 'deleted' => 0));
        
        $this->datasets['Heroes']->reset();
    }
}

?>
