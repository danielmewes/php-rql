<?php

class Heroes extends Dataset
{
    protected function create()
    {
        r\dbCreate('Heroes')->run($this->conn);
        r\db('Heroes')->tableCreate('marvel', array('primary_key' => 'superhero'))->run($this->conn);
        r\db('Heroes')->tableCreate('dc_universe', array('primary_key' => 'name'))->run($this->conn);
        
        $marvelTable = r\db('Heroes')->table('marvel');
        $dcUniverseTable = r\db('Heroes')->table('dc_universe');
        
        $marvelTable->insert(array('superhero' => 'Iron Man', 'superpower' => 'Arc Reactor' ))->run($this->conn);
        $marvelTable->insert(array(array( 'superhero' => 'Wolverine', 'superpower' => 'Adamantium' ), array( 'superhero' => 'Spiderman', 'superpower' => 'spidy sense' )))->run($this->conn);

        
    }    
    
    protected function delete()
    {
        r\dbDrop('Heroes')->run($this->conn);
    }
}

?>
