<?php

namespace r\Queries\Control;

use r\Query;
use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Branch extends ValuedQuery
{
    public function __construct(Query $test, $trueBranch, $falseBranch)
    {
        $trueBranch = \r\nativeToDatumOrFunction($trueBranch);
        $falseBranch = \r\nativeToDatumOrFunction($falseBranch);

        $this->setPositionalArg(0, $test);
        $this->setPositionalArg(1, $trueBranch);
        $this->setPositionalArg(2, $falseBranch);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_BRANCH;
    }
}
