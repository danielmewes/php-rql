<?php

namespace r\Queries\Writing;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Delete extends ValuedQuery
{
    public function __construct(ValuedQuery $selection, array $opts = [])
    {
        $this->setPositionalArg(0, $selection);

        foreach ($opts as $opt => $val) {
            $this->setOptionalArg($opt, $this->nativeToDatum($val));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_DELETE;
    }
}
