<?php

class ProfilingTest extends TestCase
{
    public function run()
    {
        $profile = r\expr(1)->profile($this->conn);
        if (!is_object($profile) || !is_array($profile->toNative())) echo "Did not receive a query profile.\n";
        $profile = r\expr(1)->profile($this->conn, null, $result);
        if (!is_object($profile) || !is_array($profile->toNative())) echo "Did not receive a query profile.\n";
        if ($result->toNative() !== 1.0) echo "Wrong result when profiling.\n";
    }
}

?>
