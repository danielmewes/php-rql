<?php

namespace r\ValuedQuery;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class ImplicitVar extends ValuedQuery
{
    protected function getTermType()
    {
        return Term_TermType::PB_IMPLICIT_VAR;
    }
    public function _hasUnwrappedImplicitVar()
    {
        // A function wraps implicit variables
        return true;
    }
}
