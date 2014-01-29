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
        
        r\js('while(true) {}', 2.0)->run($this->conn, array("noreply" => true));
        $t = time(true);
        $this->conn->noreplyWait();
        if (time(true) - $t < 1.5) echo "noreplyWait did not wait.\n";
        
        r\js('while(true) {}', 2.0)->run($this->conn, array("noreply" => true));
        $t = time(true);
        $this->conn->close();
        if (time(true) - $t < 1.5) echo "close did not wait.\n";
        $this->conn->reconnect();
        
        r\js('while(true) {}', 2.0)->run($this->conn, array("noreply" => true));
        $t = time(true);
        $this->conn->reconnect();
        if (time(true) - $t < 1.5) echo "reconnect did not wait.\n";
        
        r\js('while(true) {}', 2.0)->run($this->conn, array("noreply" => true));
        $t = time(true);
        $this->conn->close(false);
        if (time(true) - $t > 0.5) echo "close did wait when it shouldn't.\n";
        $this->conn->reconnect();
        
        r\js('while(true) {}', 2.0)->run($this->conn, array("noreply" => true));
        $t = time(true);
        $this->conn->reconnect(false);
        if (time(true) - $t > 0.5) echo "reconnect did wait when it shouldn't.\n";
    }
}

?>
