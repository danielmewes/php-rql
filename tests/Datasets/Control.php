<?php

namespace r\Tests\Datasets;

use function r\db;

class Control extends Dataset
{
    public function populate()
    {
    }

    public function truncate()
    {
        db($this->db)->table('t1')->delete()->run($this->conn);
    }

    public function create()
    {
        db($this->db)->tableCreate('t1')->run($this->conn);
    }
}
