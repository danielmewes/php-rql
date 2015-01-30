<?php

class DbTest extends TestCase
{
    public function run()
    {
        $this->checkQueryResult(r\dbCreate('dbTest')->pluck('dbs_created'), array('dbs_created' => 1.0));
        $this->checkQueryResult(r\dbDrop('dbTest')->pluck('dbs_dropped'), array('dbs_dropped' => 1.0));
    }
}

?>
