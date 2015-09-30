<?php

namespace r\Exceptions;

use r\Exceptions\RqlException;

class RqlDriverError extends RqlException
{
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }

    public function __toString()
    {
        return "RqlDriverError:\n  " . $this->getMessage() . "\n";
    }
}
