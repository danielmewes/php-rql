<?php

namespace r\Queries\Joins;

use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\ProtocolBuffer\TermTermType;

class EqJoin extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attribute, ValuedQuery $otherSequence, $opts = null)
    {
        $attribute = $this->nativeToDatumOrFunction($attribute);
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $attribute);
        $this->setPositionalArg(2, $otherSequence);
        if (isset($opts)) {
            if (!is_array($opts)) {
                throw new RqlDriverError("opts argument must be an array");
            }
            foreach ($opts as $k => $v) {
                $this->setOptionalArg($k, $this->nativeToDatum($v));
            }
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_EQ_JOIN;
    }
}
