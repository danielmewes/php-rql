<?php

namespace r\ValuedQuery;

use r\Exceptions\RqlDriverError;
use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class RObject extends ValuedQuery
{
    public function __construct($object)
    {
        if (!is_array($object)) {
            throw new RqlDriverError("Argument to r\\Object must be an array.");
        }
        $i = 0;
        foreach ($object as $v) {
            $this->setPositionalArg($i++, $this->nativeToDatum($v));
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_OBJECT;
    }
}
