<?php

namespace r\Queries\Dbs;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class DbList extends ValuedQuery
{
    protected function getTermType()
    {
        return TermTermType::PB_DB_LIST;
    }
}
