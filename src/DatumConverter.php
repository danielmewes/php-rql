<?php

namespace r;

use ArrayObject;
use DateTime;
use DateTimeInterface;
use r\Datum\ArrayDatum;
use r\Datum\BoolDatum;
use r\Datum\Datum;
use r\Datum\NullDatum;
use r\Datum\NumberDatum;
use r\Datum\ObjectDatum;
use r\Datum\StringDatum;
use r\Exceptions\RqlDriverError;
use r\FunctionQuery\RFunction;
use r\Queries\Dates\Iso8601;
use r\ValuedQuery\MakeArray;
use r\ValuedQuery\MakeObject;
use r\ValuedQuery\RVar;
use ReflectionFunction;
use stdClass;

class DatumConverter
{
    public function nativeToDatum($v)
    {
        if ($v instanceof Query) {
            return $v;
        }

        if ($v instanceof stdClass || $v instanceof ArrayObject) {
            $v = (array) $v;
        }

        if (is_array($v)) {
            $datumArray = [];
            $hasNonNumericKey = $mustUseMakeTerm = false;

            foreach ($v as $key => $val) {
                if (is_string($key)) {
                    $hasNonNumericKey = true;
                }

                if ($val instanceof Query && !$val instanceof Datum) {
                    $subDatum = $val;
                    $mustUseMakeTerm = true;
                } else {
                    $subDatum = $this->nativeToDatum($val);
                    if (!$subDatum instanceof Datum) {
                        $mustUseMakeTerm = true;
                    }
                }

                $datumArray[$key] = $subDatum;
            }

            // Note: In the case of $hasNonNumericKey === false, we cannot
            //   know if we should convert to an array or an object. We
            //   currently assume array, but this is not overly clean.
            //   Of course the user always has the option to wrap data
            //   into a Datum manually.
            //   We use this behavior because it is consistent to json_encode,
            //   which we sometimes use as a transparent replacement for
            //   $this->nativeToDatum().
            if ($hasNonNumericKey) {
                return $mustUseMakeTerm ? new MakeObject($datumArray) : new ObjectDatum($datumArray);
            }

            return $mustUseMakeTerm ? new MakeArray($datumArray) : new ArrayDatum($datumArray);
        }

        if (null === $v) {
            return new NullDatum();
        }

        if (is_bool($v)) {
            return new BoolDatum($v);
        }

        if (is_int($v) || is_float($v)) {
            return new NumberDatum($v);
        }

        if (is_string($v)) {
            return new StringDatum($v);
        }

        if ($v instanceof DateTimeInterface) {
            return new Iso8601($v->format(DateTime::ATOM));
        }

        throw new RqlDriverError('Unhandled type '.get_class($v));
    }

    // ------------- Helpers -------------
    public static function decodedJSONToDatum($json)
    {
        if (null === $json) {
            return NullDatum::decodeServerResponse($json);
        }
        if (is_bool($json)) {
            return BoolDatum::decodeServerResponse($json);
        }
        if (is_int($json) || is_float($json)) {
            return NumberDatum::decodeServerResponse($json);
        }
        if (is_string($json)) {
            return StringDatum::decodeServerResponse($json);
        }
        if (is_array($json)) {
            return ArrayDatum::decodeServerResponse($json);
        }
        if (is_object($json)) {
            return ObjectDatum::decodeServerResponse($json);
        }

        throw new RqlDriverError('Unhandled type '.get_class($json));
    }

    public function tryEncodeAsJson($v)
    {
        if ($this->canEncodeAsJson($v)) {
            // PHP by default loses some precision when encoding floats, so we temporarily
            // bump up the `precision` option to avoid this.
            // The 17 assumes IEEE-754 double precision numbers.
            // Source: http://docs.oracle.com/cd/E19957-01/806-3568/ncg_goldberg.html
            //         "The same argument applied to double precision shows that 17 decimal
            //          digits are required to recover a double precision number."
            $previousPrecision = ini_set('precision', 17);
            $json = json_encode($v);
            if (false !== $previousPrecision) {
                ini_set('precision', $previousPrecision);
            }
            if (false === $json) {
                throw new RqlDriverError('Failed to encode document as JSON: '.json_last_error());
            }
        }

        return $json ?? false;
    }

    public function canEncodeAsJson($v)
    {
        if (null === $v || is_scalar($v)) {
            return true;
        }

        if (is_array($v)) {
            foreach ($v as $key => $val) {
                if (!is_numeric($key) && !is_string($key)) {
                    return false;
                }
                if (!$this->canEncodeAsJson($val)) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function wrapImplicitVar(Query $q)
    {
        if ($q->hasUnwrappedImplicitVar()) {
            return new RFunction([new RVar('_')], $q);
        }

        return $q;
    }

    public function nativeToFunction($f)
    {
        if ($f instanceof Query) {
            return $this->wrapImplicitVar($f);
        }

        $reflection = new ReflectionFunction($f);

        $args = [];
        foreach ($reflection->getParameters() as $param) {
            $args[] = new RVar($param->getName());
        }

        if (null === $result = $f(...$args)) {
            // In case of null, assume that the user forgot to add a return.
            // If null is the intended value, r\expr() should be wrapped around the return value.
            throw new RqlDriverError("The function did not evaluate to a value (missing return?). If the function is intended to return `null,` please use `return r\expr(null);`.");
        }

        if ($result instanceof Query) {
            return new RFunction($args, $result);
        }

        return new RFunction($args, $this->nativeToDatum($result));
    }

    public function nativeToDatumOrFunction($f, $wrapImplicit = true)
    {
        if (!$f instanceof Query) {
            try {
                $f = $this->nativeToDatum($f);
                if (!$f instanceof Datum) {
                    // $f is not a simple datum. Wrap it into a function:
                    $f = new RFunction([new RVar('_')], $f);
                }
            } catch (RqlDriverError $e) {
                $f = $this->nativeToFunction($f);
            }
        }

        if ($wrapImplicit) {
            return $this->wrapImplicitVar($f);
        }

        return $f;
    }
}
