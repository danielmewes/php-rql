<?php

namespace r\Tests\Datasets;

use function r\db;
use function r\point;

class Geo extends Dataset
{
    public function create()
    {
        db($this->db)->tableCreate('geo')->run($this->conn);
    }

    public function populate()
    {
        $geoTable = db($this->db)->table('geo');
        $geoTable->insert(['geo' => point(1.0, 1.0)])->run($this->conn);
        $geoTable->insert(['geo' => point(1.0, 0.0)])->run($this->conn);
    }

    public function truncate()
    {
        db($this->db)->table('geo')->delete()->run($this->conn);
    }
}
