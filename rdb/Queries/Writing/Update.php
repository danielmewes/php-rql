<?php

namespace r\Queries\Writing;

use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\ValuedQuery\Json;
use r\ProtocolBuffer\TermTermType;

class Update extends ValuedQuery
{
    public function __construct(ValuedQuery $selection, $delta, $opts = null)
    {
        if (isset($opts) && !\is_array($opts)) {
            throw new RqlDriverError("Options must be an array.");
        }
        if (!(is_object($delta) && is_subclass_of($delta, "\\r\\Query"))) {
            try {
                $json = $this->tryEncodeAsJson($delta);
                if ($json !== false) {
                    $delta = new Json($json);
                } else {
                    $delta = $this->nativeToDatum($delta);
                }
            } catch (RqlDriverError $e) {
                $delta = $this->nativeToFunction($delta);
            }
        }
        $delta = $this->wrapImplicitVar($delta);

        $this->setPositionalArg(0, $selection);
        $this->setPositionalArg(1, $delta);
        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, $this->nativeToDatum($val));
            }
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_UPDATE;
    }
}
