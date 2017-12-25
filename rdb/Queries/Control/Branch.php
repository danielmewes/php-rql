<?php

namespace r\Queries\Control;

use r\Exceptions\RqlException;
use r\Query;
use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Branch extends ValuedQuery
{
    public function __construct(array $branches)
    {
        if(count($branches) % 2 == 1)
        {
            // poping 'false' branch from other branches
            $falseBranch = $this->nativeToDatumOrFunction(array_pop($branches), false);

            // for each remaning branch, if the index is odd, this is a branch, so we convert, otherwise, we directly position the argument
            foreach ($branches as $i => $branch)
            {
                if($i % 2 == 1)
                {
                    // branch, so we convert
                    $branch = $this->nativeToDatumOrFunction($branch, false);
                }

                $this->setPositionalArg($i, $branch);
            }

            // pushing the 'false' branch at the end of positional args
            $this->setPositionalArg(count($branches), $falseBranch);
        }
        else
        {
            throw new RqlException(__METHOD__ . ' must have at least 3 parameters, or an odd parameter count.');
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_BRANCH;
    }
}
