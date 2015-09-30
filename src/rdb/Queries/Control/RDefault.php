<?php

namespace r\Queries\Control;

use r\Query;
use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class RDefault extends ValuedQuery
{
    public function __construct(Query $query, $defaultCase)
    {
        $defaultCase = \r\nativeToDatumOrFunction($defaultCase);

        $this->setPositionalArg(0, $query);
        $this->setPositionalArg(1, $defaultCase);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_DEFAULT;
    }
}
