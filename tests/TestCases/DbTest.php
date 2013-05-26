<?php

class DbTest extends TestCase
{
    public function run()
    {
        $this->checkQueryResult(r\dbCreate('dbTest'), array('created' => 1.0));
        $this->checkQueryResult(r\dbDrop('dbTest'), array('dropped' => 1.0));
    }
}

?>
