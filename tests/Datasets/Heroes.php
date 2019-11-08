<?php

namespace r\Tests\Datasets;

use function r\db;

class Heroes extends Dataset
{
    public function create()
    {
        db($this->db)->tableCreate('marvel', ['primary_key' => 'superhero'])->run($this->conn);
        db($this->db)->tableCreate('dc_universe', ['primary_key' => 'name'])->run($this->conn);
    }

    public function populate()
    {
        $marvelTable = db($this->db)->table('marvel');
        $dcUniverseTable = db($this->db)->table('dc_universe');
        $marvelTable->insert(['superhero' => 'Iron Man', 'superpower' => 'Arc Reactor', 'combatPower' => 2.0, 'compassionPower' => 1.5])->run($this->conn);
        $marvelTable->insert([['superhero' => 'Wolverine', 'superpower' => 'Adamantium', 'combatPower' => 5.0, 'compassionPower' => 2.0], ['superhero' => 'Spiderman', 'superpower' => 'spidy sense', 'combatPower' => 2.0, 'compassionPower' => 2.5]])->run($this->conn);
    }

    public function truncate()
    {
        db($this->db)->table('marvel')->delete()->run($this->conn);
        db($this->db)->table('dc_universe')->delete()->run($this->conn);
    }
}
