<?php

namespace r\Queries\Index;

use r\ValuedQuery\ValuedQuery;
use r\Queries\Tables\Table;
use r\pb\Term_TermType;

class IndexWait extends ValuedQuery
{
    public function __construct(Table $table, $indexNames = null)
    {
        if (isset($indexNames) && !is_array($indexNames)) {
            $indexNames = array($indexNames);
        }

        $this->setPositionalArg(0, $table);
        if (isset($indexNames)) {
            $pos = 1;
            foreach ($indexNames as $v) {
                $this->setPositionalArg($pos++, \r\nativeToDatum($v));
            }
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_INDEX_WAIT;
    }
}
