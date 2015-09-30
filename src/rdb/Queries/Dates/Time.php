<?php

namespace r\Queries\Dates;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Time extends ValuedQuery
{
    public function __construct($year, $month, $day, $hourOrTimezone, $minute = null, $second = null, $timezone = null)
    {
        $year = \r\nativeToDatum($year);
        $month = \r\nativeToDatum($month);
        $day = \r\nativeToDatum($day);
        $hourOrTimezone = \r\nativeToDatum($hourOrTimezone);
        if (isset($minute)) {
            $minute = \r\nativeToDatum($minute);
        }
        if (isset($second)) {
            $second = \r\nativeToDatum($second);
        }
        if (isset($timezone)) {
            $timezone = \r\nativeToDatum($timezone);
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
        return Term_TermType::PB_TIME;
    }
}
