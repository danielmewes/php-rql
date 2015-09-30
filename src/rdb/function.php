<?php

namespace r;

use ReflectionFunction;
use r\Datnum\NumberDatum;
use r\Datnum\ArrayDatum;
use r\FunctionQuery\RFunction;
use r\Exceptions\RqlDriverError;
use r\ValuedQuery\RVar;

function wrapImplicitVar(Query $q)
{
    if ($q->_hasUnwrappedImplicitVar()) {
        return new RFunction(array(new RVar('_')), $q);
    } else {
        return $q;
    }
}

function nativeToFunction($f)
{
    if (is_object($f) && is_subclass_of($f, '\r\Query')) {
        return wrapImplicitVar($f);
    }

    $reflection = new ReflectionFunction($f);

    $args = array();
    foreach ($reflection->getParameters() as $param) {
        $args[] = new RVar($param->getName());
    }
    $result = $reflection->invokeArgs($args);

    if (!(is_object($result) && is_subclass_of($result, "\\r\\Query"))) {
        if (!isset($result)) {
            // In case of null, assume that the user forgot to add a return.
            // If null is the intended value, r\expr() should be wrapped around the return value.
            throw new RqlDriverError("The function did not evaluate to a query (missing return?).");
        } else {
            $result = \r\nativeToDatum($result);
        }
    }

    return new RFunction($args, $result);
}

function nativeToDatumOrFunction($f)
{
    if (!(is_object($f) && is_subclass_of($f, '\r\Query'))) {
        try {
            $f = \r\nativeToDatum($f);
            if (!is_subclass_of($f, '\r\Datum\Datum')) {
                // $f is not a simple datum. Wrap it into a function:
                $f = new RFunction(array(new RVar('_')), $f);
            }
        } catch (RqlDriverError $e) {
            $f = nativeToFunction($f);
        }
    }
    return wrapImplicitVar($f);
}

function systemInfo()
{
    global $__PHP_RQL_VERSION;
    $result = "";
    $result .=  "PHP-RQL Version: " . $__PHP_RQL_VERSION . "\n";
    return $result;
}
