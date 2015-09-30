<?php

namespace r\Queries\Dbs;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class DbList extends ValuedQuery
{
    protected function getTermType()
    {
        return Term_TermType::PB_DB_LIST;
    }
}
