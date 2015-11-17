<?php

namespace r\Datum;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;
use r\Exceptions\RqlDriverError;

abstract class Datum extends ValuedQuery
{
    private $value;

    public function __construct($value = null)
    {
        if (isset($value)) {
            $this->setValue($value);
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_DATUM;
    }

    public function toNative($opts)
    {
        return $this->getValue();
    }

    public function __toString()
    {
        return "" . $this->getValue();
    }

    public function toString(&$backtrace)
    {
        $result = $this->__toString();
        if (is_null($backtrace)) {
            return $result;
        } else {
            if ($backtrace === false) {
                return str_repeat(" ", strlen($result));
            }
            $backtraceFrame = $backtrace->consumeFrame();
            if ($backtraceFrame !== false) {
                throw new RqlDriverError(
                    "Internal Error: The backtrace says that we should have an argument in a Datum. "
                    . "This is not possible."
                );
            }
            return str_repeat("~", strlen($result));
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($val)
    {
        $this->value = $val;
    }
}
