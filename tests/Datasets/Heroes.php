<?php

class Heroes extends Dataset
{
    protected function create()
    {
        r\dbCreate('Heroes')->run($this->conn);
        r\db('Heroes')->tableCreate('marvel')->run($this->conn);
        r\db('Heroes')->tableCreate('dc_universe')->run($this->conn);
        
        $marvelTable = r\db('Heroes')->table('marvel', array('primary_key' => 'superhero'));
        $dcUniverseTable = r\db('Heroes')->table('dc_universe', array('primary_key' => 'name'));
        
        $marvelTable->insert(array('superhero' => 'Iron Man', 'superpower' => 'Arc Reactor' ))->run($conn);
        $marvelTable->insert(array(array( 'superhero' => 'Wolverine', 'superpower' => 'Adamantium' ), array( 'superhero' => 'Spiderman', 'superpower' => 'spidy sense' )))->run($conn);

        
    }    
    
    protected function delete()
    {
        r\dbDrop('Heroes')->run($this->conn);
    }
}

?>
