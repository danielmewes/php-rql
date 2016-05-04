<?php

namespace r\Tests\Datasets;

// use function r\db;
// use function r\dbCreate;
// use function r\dbDrop;

class Huge extends Dataset
{
    public function create()
    {
        //dbCreate('Huge')->run($this->conn);
    }

    public function populate()
    {
        // Prepare a table with 5000 rows

        $doc = array('key' => str_repeat("var", 1000));
        $docs = array_fill(0, 5000, $doc);
        \r\db($this->db)->table('t5000')->insert($docs)->run($this->conn);
    }

    public function truncate()
    {
        \r\db($this->db)->table('t5000')->delete()->run($this->conn);
    }
}
