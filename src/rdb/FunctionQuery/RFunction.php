<?php

namespace r\FunctionQuery;

use r\Query;
use r\FunctionQuery\FunctionQuery;
use r\Datum\NumberDatum;
use r\Datum\ArrayDatum;
use r\pb\Term_TermType;
use r\Exceptions\RqlDriverError;

class RFunction extends FunctionQuery
{
    public function __construct($args, Query $top)
    {
        if (!is_array($args)) {
            throw new RqlDriverError("Arguments must be an array.");
        }
        foreach ($args as &$arg) {
            if (!is_a($arg, 'r\ValuedQuery\RVar')) {
                throw new RqlDriverError("Arguments must be RVar variables.");
            }
            $arg = new NumberDatum($arg->getId());
            unset($arg);
        }

        $this->setPositionalArg(0, new ArrayDatum($args));
        $this->setPositionalArg(1, $top);
    }

    public function _hasUnwrappedImplicitVar()
    {
        // A function wraps implicit variables
        return false;
    }

    protected function getTermType()
    {
        return Term_TermType::PB_FUNC;
    }
}
