<?php

namespace r\Queries\Selecting;

use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\ProtocolBuffer\TermTermType;

class Between extends ValuedQuery
{
    public function __construct(ValuedQuery $selection, $leftBound, $rightBound, $opts = null)
    {
        $leftBound = $this->nativeToDatum($leftBound);
        $rightBound = $this->nativeToDatum($rightBound);

        $this->setPositionalArg(0, $selection);
        $this->setPositionalArg(1, $leftBound);
        $this->setPositionalArg(2, $rightBound);
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
        return TermTermType::PB_BETWEEN;
    }
}
