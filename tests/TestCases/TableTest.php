<?php

class TableTest extends TestCase
{
    public function run()
    {
        // Test management operations
        r\dbCreate('tableTest')->run($this->conn);
        
        $this->checkQueryResult(r\db('tableTest')->tableCreate('t1', array('durability' => 'soft', 'primary_key' => 'p')), array('created' => 1.0));
        $this->checkQueryResult(r\db('tableTest')->table('t1')->insert(array( 'p' => 'foo')), array('unchanged' => 0, 'skipped' => 0, 'replaced' => 0, 'inserted' => 1, 'errors' => 0, 'deleted' => 0));
        
        $this->checkQueryResult(r\db('tableTest')->tableList(), array('t1'));
        
        // TODO: These index tests are kind of duplicates of IndexTest...
        $this->checkQueryResult(r\db('tableTest')->table('t1')->indexCreate('akey'), array('created' => 1.0));
        $this->checkQueryResult(r\db('tableTest')->table('t1')->indexCreate('bfun', r\row('p')), array('created' => 1.0));
        $this->checkQueryResult(r\db('tableTest')->table('t1')->indexCreate('cfun', function($r) { return r\expr(5); } ), array('created' => 1.0));
        $this->checkQueryResult(r\db('tableTest')->table('t1')->indexList(), array('akey', 'bfun', 'cfun'));
        $this->checkQueryResult(r\db('tableTest')->table('t1')->indexDrop('akey'), array('dropped' => 1.0));
        $this->checkQueryResult(r\db('tableTest')->table('t1')->indexDrop('bfun'), array('dropped' => 1.0));
        $this->checkQueryResult(r\db('tableTest')->table('t1')->indexDrop('cfun'), array('dropped' => 1.0));
       
        $this->checkQueryResult(r\db('tableTest')->table('t1')->sync(), array('synced' => 1.0));

        $this->checkQueryResult(r\db('tableTest')->tableDrop('t1'), array('dropped' => 1.0));
        
        r\dbDrop('tableTest')->run($this->conn);
        
        
        // Test general whole-table queries
        $this->requireDataset('Heroes');
    
        $testResult = r\db('Heroes')->table('marvel')->orderBy('superhero')->run($this->conn)->toNative();
        $this->checkQueryResult(r\expr($testResult)->count(), 3.0);
        
        $this->datasets['Heroes']->reset();
    }
}

?>
