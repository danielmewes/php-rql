<?php

namespace r\ValuedQuery;

use r\ProtocolBuffer\TermTermType;

class MakeObject extends ValuedQuery
{
    public function __construct(array $value)
    {
        foreach ($value as $key => $val) {
            $this->setOptionalArg($key, $val);
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_MAKE_OBJ;
    }
}
