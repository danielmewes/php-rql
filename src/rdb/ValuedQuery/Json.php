<?php

namespace r\ValuedQuery;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;
use r\Exceptions\RqlDriverError;
use r\Datum\StringDatum;

class Json extends ValuedQuery
{
    public function __construct($json)
    {
        if (!(is_object($json) && is_subclass_of($json, '\r\Query'))) {
            if (!is_string($json)) {
                throw new RqlDriverError("JSON must be a string.");
            }
            $json = new StringDatum($json);
        }
        $this->setPositionalArg(0, $json);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_JSON;
    }
}
