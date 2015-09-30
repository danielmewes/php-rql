<?php

namespace r\Queries\Writing;

use r\ValuedQuery\ValuedQuery;
use r\ValuedQuery\Json;
use r\pb\Term_TermType;

class Replace extends ValuedQuery
{
    public function __construct(ValuedQuery $selection, $delta, $opts)
    {
        if (isset($opts) && !\is_array($opts)) {
            throw new RqlDriverError("Options must be an array.");
        }
        if (!(is_object($delta) && is_subclass_of($delta, "\\r\\Query"))) {
            // If we can make it an object, we will wrap that object into a function.
            // Otherwise, we will try to make it a function.
            try {
                $json = \r\tryEncodeAsJson($delta);
                if ($json !== false) {
                    $delta = new Json($json);
                } else {
                    $delta = \r\nativeToDatum($delta);
                }
            } catch (RqlDriverError $e) {
                $delta = \r\nativeToFunction($delta);
            }
        }
        $delta = \r\wrapImplicitVar($delta);

        $this->setPositionalArg(0, $selection);
        $this->setPositionalArg(1, $delta);
        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, \r\nativeToDatum($val));
            }
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_REPLACE;
    }
}
