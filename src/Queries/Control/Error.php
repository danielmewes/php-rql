<?php

namespace r\Queries\Control;

use r\Datum\StringDatum;
use r\ProtocolBuffer\TermTermType;
use r\Query;
use r\ValuedQuery\ValuedQuery;

class Error extends ValuedQuery
{
    public function __construct($message = null)
    {
        if (isset($message)) {
            if (!$message instanceof Query) {
                $message = new StringDatum($message);
            }
            $this->setPositionalArg(0, $message);
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_ERROR;
    }
}
