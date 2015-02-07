<?php

class DbTest extends TestCase
{
    public function run()
    {
        $this->checkQueryResult(r\dbCreate('dbTest')->pluck('dbs_created'), array('dbs_created' => 1.0));
        $this->checkQueryResult(r\db('dbTest')->wait(), array('ready' => 0.0, 'status_changes' => array()));
        $this->checkQueryResult(r\db('dbTest')->rebalance(), array());
        $this->checkQueryResult(r\db('dbTest')->reconfigure(array('shards' => 1, 'replicas' => 1)), array());
        $this->checkQueryResult(r\dbDrop('dbTest')->pluck('dbs_dropped'), array('dbs_dropped' => 1.0));
    }
}

?>
