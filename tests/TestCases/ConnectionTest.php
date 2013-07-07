<?php

class ConnectionTest extends TestCase
{
    public function run()
    {
        $this->checkQueryResult(r\expr(true), true); // Pre-test
        $this->conn->reconnect();
        $this->checkQueryResult(r\expr(true), true); // Reconnect validation
        
        $this->conn->setTimeout(1);
        $triggeredDriverError = false;
        try {
            r\js('while(true) {}', 2.0)->run($this->conn);
        } catch (r\RqlUserError $e) {
        } catch (r\RqlDriverError $e) {
            $triggeredDriverError = true;
            $this->conn->reconnect();
        }
        if (!$triggeredDriverError) echo "Connection did not time out as it should have.\n";
        
        $this->conn->setTimeout(60);
        $triggeredDriverError = false;
        try {
            r\js('while(true) {}', 2.0)->run($this->conn);
        } catch (r\RqlUserError $e) {
        } catch (r\RqlDriverError $e) {
            $triggeredDriverError = true;
            $this->conn->reconnect();
        }
        if ($triggeredDriverError) echo "Connection did time out when it should not have.\n";
    }
}

?>
