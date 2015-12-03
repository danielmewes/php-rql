<?php

namespace r\Queries\Control;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class RDo extends ValuedQuery
{
    public function __construct($args, $inExpr)
    {
        $inExpr = $this->nativeToFunction($inExpr);
        $this->setPositionalArg(0, $inExpr);

        $i = 1;
        if (!is_array($args)) {
            $args = array($args);
        }
        foreach ($args as &$arg) {
            if (!(is_object($arg) && is_subclass_of($arg, '\r\Query'))) {
                $arg = $this->nativeToDatum($arg);
            }
            $this->setPositionalArg($i++, $arg);
            unset($arg);
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_FUNCALL;
    }
}
