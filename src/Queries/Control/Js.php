<?php

namespace r\Queries\Control;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;
use r\Datum\NumberDatum;
use r\Datum\StringDatum;
use r\FunctionQuery\FunctionQuery;

class Js extends FunctionQuery
{
    public function __construct($code, $timeout = null)
    {
        if (isset($timeout)) {
            $timeout = new NumberDatum($timeout);
        }
        if (!(is_object($code) && is_subclass_of($code, '\r\Query'))) {
            $code = new StringDatum($code);
        }

        $this->setPositionalArg(0, $code);
        if (isset($timeout)) {
            $this->setOptionalArg('timeout', $timeout);
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_JAVASCRIPT;
    }
}
