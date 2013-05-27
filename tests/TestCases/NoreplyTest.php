<?php

class NoreplyTest extends TestCase
{
    public function run()
    {
        r\dbCreate('NoreplyTest')->run($this->conn);
        
        $this->conn->useDb('NoreplyTest');
        
        r\tableCreate('t')->run($this->conn);
        
        $result = r\table('t')->insert(array('id' => 1, 'key' => 'val'))->run($this->conn, array('noreply' => true));
        if (!is_null($result)) echo "Noreply query returned a result\n";
        
        $this->checkQueryResult(r\table('t')->get(1)->attr('key'), 'val');
        
        r\dbDrop('NoreplyTest')->run($this->conn);
    }
}

?>
