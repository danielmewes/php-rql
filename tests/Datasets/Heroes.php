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
        
        $marvelTable->insert(array('superhero' => 'Iron Man', 'superpower' => 'Arc Reactor', 'combatPower' => 2.0, 'compassionPower' => 1.5 ))->run($this->conn);
        $marvelTable->insert(array(array( 'superhero' => 'Wolverine', 'superpower' => 'Adamantium', 'combatPower' => 5.0, 'compassionPower' => 2.0 ), array( 'superhero' => 'Spiderman', 'superpower' => 'spidy sense', 'combatPower' => 2.0, 'compassionPower' => 2.5 )))->run($this->conn);

        
    }    
    
    protected function delete()
    {
        r\dbDrop('Heroes')->run($this->conn);
    }
}

?>
