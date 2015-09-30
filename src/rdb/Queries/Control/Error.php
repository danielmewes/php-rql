<?php

namespace r\Queries\Control;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;
use r\Datum\StringDatum;

class Error extends ValuedQuery
{
    public function __construct($message = null)
    {
        if (isset($message)) {
            if (!(is_object($message) && is_subclass_of($message, '\r\Query'))) {
                $message = new StringDatum($message);
            }
            $this->setPositionalArg(0, $message);
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_ERROR;
    }
}
