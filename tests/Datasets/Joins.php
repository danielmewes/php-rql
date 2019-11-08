<?php

namespace r\Tests\Datasets;

use function r\db;
use function r\dbCreate;

class Joins extends Dataset
{
    public function create()
    {
        dbCreate($this->db)->run($this->conn);
        db($this->db)->tableCreate('t1')->run($this->conn);
        db($this->db)->tableCreate('t2')->run($this->conn);
        db($this->db)->table('t1')->indexCreate('other')->run($this->conn);
        db($this->db)->table('t2')->indexCreate('other')->run($this->conn);
    }

    public function populate()
    {
        $t1 = db($this->db)->table('t1');
        $t2 = db($this->db)->table('t2');
        $t1->insert(['id' => 1, 'other' => 'a'])->run($this->conn);
        $t1->insert(['id' => 2, 'other' => 'a'])->run($this->conn);
        $t1->insert(['id' => 3, 'other' => 'b'])->run($this->conn);
        $t2->insert(['id' => 'a', 'other' => 1])->run($this->conn);
        $t2->insert(['id' => 'b', 'other' => 1])->run($this->conn);
        $t2->insert(['id' => 'c', 'other' => 5])->run($this->conn);
    }

    public function truncate()
    {
        db($this->db)->table('t1')->delete()->run($this->conn);
        db($this->db)->table('t2')->delete()->run($this->conn);
    }
}
