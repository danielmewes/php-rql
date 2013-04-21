<?php namespace r;

// ------------- RethinkDB Exceptions -------------
class RqlDriverError extends \Exception
{
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }
    public function __toString() {
        return "RQL Driver Error: " . $this->getMessage() . "\n";
    }
}

class RqlUserError extends \Exception
{
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }
    public function __toString() {
        return "RQL User Error: " . $this->getMessage() . "\n";
    }
    // TODO: Add RethinkDB backtrace capability
}

?>
