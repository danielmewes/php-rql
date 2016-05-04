<?php

namespace r\ValuedQuery;

use r\Query;
use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Info extends ValuedQuery
{
    public function __construct(Query $onQuery)
    {
        $this->setPositionalArg(0, $onQuery);
    }

    protected function getTermType()
    {
        return TermTermType::PB_INFO;
    }
}
