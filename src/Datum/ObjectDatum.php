<?php

namespace r\Datum;

use ArrayObject;
use DateInterval;
use DateTime;
use DateTimeZone;
use r\DatumConverter;
use r\Exceptions\RqlDriverError;
use r\Query;

class ObjectDatum extends Datum
{
    public function encodeServerRequest()
    {
        $jsonValue = $this->getValue();
        foreach ($jsonValue as $key => &$val) {
            $val = $val->encodeServerRequest();
            unset($val);
        }

        return (object) $jsonValue;
    }

    public static function decodeServerResponse($json)
    {
        $jsonObject = (array) $json;
        foreach ($jsonObject as $key => &$val) {
            $val = DatumConverter::decodedJSONToDatum($val);
            unset($val);
        }
        $result = new ObjectDatum();
        $result->setValue($jsonObject);

        return $result;
    }

    public function setValue($val)
    {
        if (!is_array($val)) {
            throw new RqlDriverError('Not an array: '.$val);
        }
        foreach ($val as $k => $v) {
            if (!is_string($k) && !is_numeric($k)) {
                throw new RqlDriverError('Not a string or number: '.$k);
            }
            if (!$v instanceof Query) {
                throw new RqlDriverError('Not a Query: '.$v);
            }
        }
        parent::setValue($val);
    }

    public function toNative($opts)
    {
        $native = new ArrayObject();
        foreach ($this->getValue() as $key => $val) {
            $native[$key] = $val->toNative($opts);
        }
        // Decode BINARY pseudo-type
        if ((!isset($opts['binaryFormat']) || 'native' == $opts['binaryFormat'])
            && isset($native['$reql_type$']) && 'BINARY' == $native['$reql_type$']
        ) {
            $decodedStr = base64_decode($native['data'], true);
            if (false === $decodedStr) {
                throw new RqlDriverError('Failed to Base64 decode r\binary value "'.$native['data'].'"');
            }

            return $decodedStr;
        }
        // Decode TIME pseudo-type to DateTime
        if ((!isset($opts['timeFormat']) || 'native' == $opts['timeFormat'])
            && isset($native['$reql_type$']) && 'TIME' == $native['$reql_type$']
        ) {
            $time = $native['epoch_time'];
            // This is really stupid. It looks like we can either use `date`, which ignores microseconds,
            // or we can use `createFromFormat` which cannot handle negative epoch times.
            if ($time < 0) {
                $format = (false !== strpos($time, '.')) ? 'Y-m-d\TH:i:s.u' : 'Y-m-d\TH:i:s';
                $datetime = new DateTime(date($format, $time).$native['timezone'], new DateTimeZone('UTC'));
            } else {
                $format = (false !== strpos($time, '.')) ? '!U.u T' : '!U T';
                $datetime = DateTime::createFromFormat($format, $time.' '.$native['timezone'], new DateTimeZone('UTC'));
            }

            // This is horrible. Just because in PHP 5.3.something parsing "+01:00" as a DateTimeZone doesn't work. :(
            $tzSign = $native['timezone'][0];
            $tzHours = $native['timezone'][1].$native['timezone'][2];
            $tzMinutes = $native['timezone'][4].$native['timezone'][5];
            if ('+' == $tzSign) {
                $datetime->add(new DateInterval('PT'.$tzHours.'H'.$tzMinutes.'M'));
            } elseif ('-' == $tzSign) {
                $datetime->sub(new DateInterval('PT'.$tzHours.'H'.$tzMinutes.'M'));
            } else {
                throw new RqlDriverError('Timezone not understood: '.$native['timezone']);
            }

            return $datetime;
        }

        return $native;
    }

    public function __toString()
    {
        // Handle BINARY pseudo-type
        $val = $this->getValue();
        if (isset($val['$reql_type$']) && 'BINARY' == $val['$reql_type$']->getValue()) {
            $decodedStr = base64_decode($val['data']->getValue(), true);
            if (false === $decodedStr) {
                return 'r\\binary(ERROR)';
            }

            return "r\\binary('$decodedStr')";
        }
        $string = 'array(';
        $first = true;
        foreach ($val as $key => $val) {
            if (!$first) {
                $string .= ', ';
            }
            $first = false;
            $string .= "'".$key."' => ".$val;
        }
        $string .= ')';

        return $string;
    }
}
