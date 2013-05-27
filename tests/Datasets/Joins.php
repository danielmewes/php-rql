<?php

class Joins extends Dataset
{
    protected function create()
    {
        r\dbCreate('Joins')->run($this->conn);
        r\db('Joins')->tableCreate('t1')->run($this->conn);
        r\db('Joins')->tableCreate('t2')->run($this->conn);
        
        $t1 = r\db('Joins')->table('t1');
        $t2 = r\db('Joins')->table('t2');
        
        $t1->indexCreate('other')->run($this->conn);
        $t2->indexCreate('other')->run($this->conn);
        
        $t1->insert(array('id' => 1, 'other' => 'a' ))->run($this->conn);
        $t1->insert(array('id' => 2, 'other' => 'a' ))->run($this->conn);
        $t1->insert(array('id' => 3, 'other' => 'b' ))->run($this->conn);
        $t2->insert(array('id' => 'a', 'other' => 1 ))->run($this->conn);
        $t2->insert(array('id' => 'b', 'other' => 1 ))->run($this->conn);
        $t2->insert(array('id' => 'c', 'other' => 5 ))->run($this->conn);
    }    
    
    protected function delete()
    {
        r\dbDrop('Joins')->run($this->conn);
    }
}

?>
