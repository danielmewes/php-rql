<?php

namespace r\Queries\Control;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class RDo extends ValuedQuery
{
    public function __construct($args, $inExpr)
    {
        $inExpr = \r\nativeToFunction($inExpr);
        $this->setPositionalArg(0, $inExpr);

        $i = 1;
        if (!is_array($args)) {
            $args = array($args);
        }
        foreach ($args as &$arg) {
            if (!(is_object($arg) && is_subclass_of($arg, '\r\Query'))) {
                $arg = \r\nativeToDatum($arg);
            }
            $this->setPositionalArg($i++, $arg);
            unset($arg);
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_FUNCALL;
    }
}
