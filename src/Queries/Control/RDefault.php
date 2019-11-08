<?php

namespace r\Queries\Control;

use r\ProtocolBuffer\TermTermType;
use r\Query;
use r\ValuedQuery\ValuedQuery;

class RDefault extends ValuedQuery
{
    public function __construct(Query $query, $defaultCase)
    {
        $defaultCase = $this->nativeToDatumOrFunction($defaultCase);

        $this->setPositionalArg(0, $query);
        $this->setPositionalArg(1, $defaultCase);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_DEFAULT;
    }
}
