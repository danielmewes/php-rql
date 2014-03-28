<?php

class Huge extends Dataset
{
    protected function create()
    {
        // Prepare a table with 5000 rows
        r\dbCreate('Huge')->run($this->conn);
        r\db('Huge')->tableCreate('t5000', array('durability' => 'soft'))->run($this->conn);
        
        $doc = array('key' => str_repeat("var", 1000));
        $docs = array_fill(0, 5000, $doc);
        r\db('Huge')->table('t5000')->insert($docs)->run($this->conn);        
    }    
    
    protected function delete()
    {
        r\dbDrop('Huge')->run($this->conn);
    }
}

?>
