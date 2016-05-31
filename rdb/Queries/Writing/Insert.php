<?php

namespace r\Queries\Writing;

use r\ValuedQuery\ValuedQuery;
use r\ValuedQuery\Json;
use r\Queries\Tables\Table;
use r\Exceptions\RqlDriverError;
use r\ProtocolBuffer\TermTermType;

class Insert extends ValuedQuery
{
    public function __construct(Table $table, $document, $opts = null)
    {
        if (isset($opts) && !\is_array($opts)) {
            throw new RqlDriverError("Options must be an array.");
        }
        if (!(is_object($document) && is_subclass_of($document, '\r\Query'))) {
            $json = $this->tryEncodeAsJson($document);
            if ($json !== false) {
                $document = new Json($json);
            } else {
                $document = $this->nativeToDatum($document);
            }
        }

        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $document);
        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, $this->nativeToDatumOrFunction($val));
            }
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_INSERT;
    }
}
