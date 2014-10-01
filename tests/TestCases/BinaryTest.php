<?php

class BinaryTest extends TestCase
{
    public function run()
    {
        $this->checkQueryResult(r\binary("abcdefg"), "abcdefg");
        $this->checkQueryResult(r\binary("abcdefg\0\0foo"), "abcdefg\0\0foo");
    }
}

?>
