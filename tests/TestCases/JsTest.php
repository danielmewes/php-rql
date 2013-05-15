<?php

class JsTest extends TestCase
{
    public function run()
    {
        $this->checkQueryResult(r\js("'str1' + 'str2'"), "str1str2");
        
        $t = microtime(true);
        try {
            r\js('while(true) {}', 1.3)->run($this->conn);
        } catch (r\RqlUserError $e) {}
        if (microtime(true) - $t > 2.0 || microtime(true) - $t < 1.3) echo "Js timeout doesn't seem to work\n";
    }
}

?>
