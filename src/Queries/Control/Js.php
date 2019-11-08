<?php

namespace r\Queries\Control;

use r\Datum\NumberDatum;
use r\Datum\StringDatum;
use r\FunctionQuery\FunctionQuery;
use r\ProtocolBuffer\TermTermType;
use r\Query;

class Js extends FunctionQuery
{
    public function __construct($code, $timeout = null)
    {
        if (!$code instanceof Query) {
            $code = new StringDatum($code);
        }
        $this->setPositionalArg(0, $code);

        if (null !== $timeout) {
            $this->setOptionalArg('timeout', new NumberDatum($timeout));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_JAVASCRIPT;
    }
}
