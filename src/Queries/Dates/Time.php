<?php

namespace r\Queries\Dates;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Time extends ValuedQuery
{
    public function __construct($year, $month, $day, $hourOrTimezone, $minute = null, $second = null, $timezone = null)
    {
        $year = $this->nativeToDatum($year);
        $month = $this->nativeToDatum($month);
        $day = $this->nativeToDatum($day);
        $hourOrTimezone = $this->nativeToDatum($hourOrTimezone);

        $this->setPositionalArg(0, $year);
        $this->setPositionalArg(1, $month);
        $this->setPositionalArg(2, $day);
        $this->setPositionalArg(3, $hourOrTimezone);
        if (isset($minute)) {
            $this->setPositionalArg(4, $this->nativeToDatum($minute));
        }
        if (isset($second)) {
            $this->setPositionalArg(5, $this->nativeToDatum($second));
        }
        if (isset($timezone)) {
            $this->setPositionalArg(6, $this->nativeToDatum($timezone));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_TIME;
    }
}
