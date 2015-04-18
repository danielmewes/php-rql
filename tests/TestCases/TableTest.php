<?php

class TableTest extends TestCase
{
    public function run()
    {
        // Test management operations
        r\dbCreate('tableTest')->run($this->conn);
        
        $this->checkQueryResult(r\db('tableTest')->tableCreate('t1', array('durability' => 'soft', 'primary_key' => 'p'))->pluck('tables_created'), array('tables_created' => 1.0));
        $this->checkQueryResult(r\db('tableTest')->table('t1')->insert(array( 'p' => 'foo')), array('unchanged' => 0, 'skipped' => 0, 'replaced' => 0, 'inserted' => 1, 'errors' => 0, 'deleted' => 0));
        $this->checkQueryResult(r\db('tableTest')->table('t1')->rebalance()->pluck('rebalanced'), array('rebalanced' => 1.0));
        $this->checkQueryResult(r\db('tableTest')->table('t1')->reconfigure(array('shards' => 1, 'replicas' => 1))->pluck('reconfigured'), array('reconfigured' => 1.0));
        $this->checkQueryResult(r\db('tableTest')->table('t1')->wait()->pluck('ready'), array('ready' => 1.0));
        $this->checkQueryResult(r\db('tableTest')->table('t1')->wait(array('wait_for' => "all_replicas_ready"))->pluck('ready'), array('ready' => 1.0));
        $this->checkQueryResult(r\db('tableTest')->table('t1')->config()->pluck('name'), array('name' => "t1"));
        $this->checkQueryResult(r\db('tableTest')->table('t1')->status()->getField('status')->pluck('all_replicas_ready'), array('all_replicas_ready' => true));
        
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

        $this->checkQueryResult(r\db('tableTest')->table('t1', false)->count(), 1.0);
        $this->checkQueryResult(r\db('tableTest')->table('t1', true)->count(), 1.0);
        $this->checkQueryResult(r\db('tableTest')->table('t1', array("use_outdated" => true))->count(), 1.0);

        $this->checkQueryResult(r\db('tableTest')->tableDrop('t1')->pluck('tables_dropped'), array('tables_dropped' => 1.0));
        
        r\dbDrop('tableTest')->run($this->conn);
        
        
        // Test general whole-table queries
        $this->requireDataset('Heroes');
    
        $testResult = r\db('Heroes')->table('marvel')->orderBy('superhero')->run($this->conn);
        $this->checkQueryResult(r\expr($testResult)->count(), 3.0);
        
        $this->datasets['Heroes']->reset();
    }
}

?>
