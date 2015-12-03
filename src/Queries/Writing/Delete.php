<?php

namespace r\Queries\Writing;

use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\ProtocolBuffer\TermTermType;

class Delete extends ValuedQuery
{
    public function __construct(ValuedQuery $selection, $opts = null)
    {
        if (isset($opts) && !\is_array($opts)) {
            throw new RqlDriverError("Options must be an array.");
        }

        $this->setPositionalArg(0, $selection);

        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, $this->nativeToDatum($val));
            }
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_DELETE;
    }
}
