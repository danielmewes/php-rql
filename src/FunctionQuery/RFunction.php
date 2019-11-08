<?php

namespace r\FunctionQuery;

use r\Datum\ArrayDatum;
use r\Datum\NumberDatum;
use r\Exceptions\RqlDriverError;
use r\ProtocolBuffer\TermTermType;
use r\Query;

class RFunction extends FunctionQuery
{
    public function __construct($args, Query $top)
    {
        if (!is_array($args)) {
            throw new RqlDriverError('Arguments must be an array.');
        }
        foreach ($args as &$arg) {
            if (!is_a($arg, 'r\ValuedQuery\RVar')) {
                throw new RqlDriverError('Arguments must be RVar variables.');
            }
            $arg = new NumberDatum($arg->getId());
            unset($arg);
        }

        $this->setPositionalArg(0, new ArrayDatum($args));
        $this->setPositionalArg(1, $top);
    }

    public function hasUnwrappedImplicitVar(): bool
    {
        // A function wraps implicit variables
        return false;
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_FUNC;
    }
}
