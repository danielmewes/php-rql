<?php

class DeleteTest extends TestCase
{
    public function run()
    {
        $this->requireDataset('Heroes');
    
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->get('superman')->delete(), array('unchanged' => 0, 'skipped' => 0, 'replaced' => 0, 'inserted' => 0, 'errors' => 0, 'deleted' => 1));
        
        $count = r\db('Heroes')->table('marvel')->count()->run($this->conn);
        
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->delete(), array('unchanged' => 0, 'skipped' => 0, 'replaced' => 0, 'inserted' => 0, 'errors' => 0, 'deleted' => $count));
        
        $this->datasets['Heroes']->reset();
    }
}

?>
