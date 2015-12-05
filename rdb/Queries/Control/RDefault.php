<?php

namespace r\Queries\Control;

use r\Query;
use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class RDefault extends ValuedQuery
{
    public function __construct(Query $query, $defaultCase)
    {
        $defaultCase = $this->nativeToDatumOrFunction($defaultCase);

        $this->setPositionalArg(0, $query);
        $this->setPositionalArg(1, $defaultCase);
    }

    protected function getTermType()
    {
        return TermTermType::PB_DEFAULT;
    }
}
