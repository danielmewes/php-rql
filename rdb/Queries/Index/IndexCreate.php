<?php

namespace r\Queries\Index;

use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\Queries\Tables\Table;
use r\ProtocolBuffer\TermTermType;

class IndexCreate extends ValuedQuery
{
    public function __construct(Table $table, $indexName, $keyFunction = null, $options = null)
    {
        $indexName = $this->nativeToDatum($indexName);
        if (isset($keyFunction)) {
            $keyFunction = $this->nativeToFunction($keyFunction);
        }
        if (isset($options)) {
            if (!is_array($options)) {
                throw new RqlDriverError("Options must be an array.");
            }
            foreach ($options as $key => &$val) {
                if (!is_string($key)) {
                    throw new RqlDriverError("Option keys must be strings.");
                }
                if (!(is_object($val) && is_subclass_of($val, "\\r\\Query"))) {
                    $val = $this->nativeToDatum($val);
                }
                unset($val);
            }
        }

        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $indexName);
        if (isset($keyFunction)) {
            $this->setPositionalArg(2, $keyFunction);
        }
        if (isset($options)) {
            foreach ($options as $key => $val) {
                $this->setOptionalArg($key, $val);
            }
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_INDEX_CREATE;
    }
}
