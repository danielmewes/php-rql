<?php

namespace r\Queries\Control;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class RDo extends ValuedQuery
{
    public function __construct($args, $inExpr)
    {
        $this->setPositionalArg(0, $this->nativeToFunction($inExpr));

        $i = 1;
        foreach ((array) $args as $arg) {
            $this->setPositionalArg($i++, $this->nativeToDatum($arg));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_FUNCALL;
    }
}
