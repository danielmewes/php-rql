<?php

namespace r\ValuedQuery;

use r\ProtocolBuffer\TermTermType;
use r\Query;

class Info extends ValuedQuery
{
    public function __construct(Query $onQuery)
    {
        $this->setPositionalArg(0, $onQuery);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_INFO;
    }
}
