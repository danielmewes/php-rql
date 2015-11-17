<?php

namespace r\Exceptions;

use r\Exceptions\RqlException;

class RqlServerError extends RqlException
{

    private $query;
    private $backtrace;

    public function __construct($message, $query = null, $backtrace = null, $code = 0)
    {
        $this->query = $query;
        $this->backtrace = $backtrace;
        parent::__construct($message, $code);
    }

    public function __toString()
    {
        return "RqlServerError:\n  " . $this->getMessage() . "\n" . $this->getBacktraceString();
    }

    public function getBacktraceString()
    {
        $result = "";
        if (isset($this->query)) {
            $result .= "  Failed query:\n";
            $nullBacktrace = null;
            $result .= "    " . $this->query->toString($nullBacktrace) . "\n";
            if (isset($this->backtrace)) {
                $backtraceCopy = $this->backtrace;
                $result .= "    " . $this->query->toString($backtraceCopy) . "\n";
            }
        }
        return $result;
    }
}
