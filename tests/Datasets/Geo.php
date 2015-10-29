<?php

namespace r\Tests\Datasets;

// use function r\db;
// use function r\point;

class Geo extends Dataset
{
    public function create()
    {
        \r\db($this->db)->tableCreate('geo')->run($this->conn);
    }

    public function populate()
    {
        $geoTable = \r\db($this->db)->table('geo');

        $geoTable->insert(array('geo' => \r\point(1.0, 1.0)))->run($this->conn);
        $geoTable->insert(array('geo' => \r\point(1.0, 0.0)))->run($this->conn);
    }

    public function truncate()
    {
        \r\db($this->db)->table('geo')->delete()->run($this->conn);
    }
}
