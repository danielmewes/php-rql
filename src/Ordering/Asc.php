<?php

namespace r\Ordering;

use r\ProtocolBuffer\TermTermType;

class Asc extends Ordering
{
    public function __construct($attribute)
    {
        $attribute = $this->nativeToDatumOrFunction($attribute);
        $this->setPositionalArg(0, $attribute);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_ASC;
    }
}
