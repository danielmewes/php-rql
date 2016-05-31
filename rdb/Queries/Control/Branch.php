<?php

namespace r\Queries\Control;

use r\Query;
use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Branch extends ValuedQuery
{
    public function __construct(Query $test, $trueBranch, $falseBranch)
    {
        $trueBranch = $this->nativeToDatumOrFunction($trueBranch, false);
        $falseBranch = $this->nativeToDatumOrFunction($falseBranch, false);

        $this->setPositionalArg(0, $test);
        $this->setPositionalArg(1, $trueBranch);
        $this->setPositionalArg(2, $falseBranch);
    }

    protected function getTermType()
    {
        return TermTermType::PB_BRANCH;
    }
}
