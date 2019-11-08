<?php

namespace r\ValuedQuery;

use r\ProtocolBuffer\TermTermType;

class RObject extends ValuedQuery
{
    public function __construct(array $object)
    {
        $i = 0;
        foreach ($object as $v) {
            $this->setPositionalArg($i++, $this->nativeToDatum($v));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_OBJECT;
    }
}
