<?php

namespace r\Tests\Datasets;

// use function r\db;
// use function r\dbCreate;
// use function r\dbDrop;

class Heroes extends Dataset
{
    public function create()
    {
        \r\db($this->db)
            ->tableCreate('marvel', array('primary_key' => 'superhero'))
            ->run($this->conn);
        \r\db($this->db)
            ->tableCreate('dc_universe', array('primary_key' => 'name'))
            ->run($this->conn);
    }

    public function populate()
    {
        $marvelTable = \r\db($this->db)->table('marvel');
        $dcUniverseTable = \r\db($this->db)->table('dc_universe');

        $marvelTable->insert(
            array(
                'superhero' => 'Iron Man',
                'superpower' => 'Arc Reactor',
                'combatPower' => 2.0,
                'compassionPower' => 1.5
            )
        )->run($this->conn);
        $marvelTable->insert(
            array(
                array(
                    'superhero' => 'Wolverine',
                    'superpower' => 'Adamantium',
                    'combatPower' => 5.0,
                    'compassionPower' => 2.0
                ),
                array(
                    'superhero' => 'Spiderman',
                    'superpower' => 'spidy sense',
                    'combatPower' => 2.0,
                    'compassionPower' => 2.5
                )
            )
        )->run($this->conn);
    }

    public function truncate()
    {
        \r\db($this->db)->table('marvel')->delete()->run($this->conn);
        \r\db($this->db)->table('dc_universe')->delete()->run($this->conn);
    }
}
