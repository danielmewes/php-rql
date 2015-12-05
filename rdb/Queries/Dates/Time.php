<?php

namespace r\Queries\Dates;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Time extends ValuedQuery
{
    public function __construct($year, $month, $day, $hourOrTimezone, $minute = null, $second = null, $timezone = null)
    {
        $year = $this->nativeToDatum($year);
        $month = $this->nativeToDatum($month);
        $day = $this->nativeToDatum($day);
        $hourOrTimezone = $this->nativeToDatum($hourOrTimezone);
        if (isset($minute)) {
            $minute = $this->nativeToDatum($minute);
        }
        if (isset($second)) {
            $second = $this->nativeToDatum($second);
        }
        if (isset($timezone)) {
            $timezone = $this->nativeToDatum($timezone);
        }

        $this->setPositionalArg(0, $year);
        $this->setPositionalArg(1, $month);
        $this->setPositionalArg(2, $day);
        $this->setPositionalArg(3, $hourOrTimezone);
        if (isset($minute)) {
            $this->setPositionalArg(4, $minute);
        }
        if (isset($second)) {
            $this->setPositionalArg(5, $second);
        }
        if (isset($timezone)) {
            $this->setPositionalArg(6, $timezone);
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_TIME;
    }
}
