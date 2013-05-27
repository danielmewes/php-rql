<?php

class Control extends Dataset
{
    protected function create()
    {
        r\dbCreate('Control')->run($this->conn);
        r\db('Control')->tableCreate('t1')->run($this->conn);
    }    
    
    protected function delete()
    {
        r\dbDrop('Control')->run($this->conn);
    }
}

?>
