<?php namespace r;

class Now extends ValuedQuery
{
    public function __construct() {
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_NOW;
    }
}

class Time extends ValuedQuery
{
    public function __construct($year, $month, $day, $hourOrTimezone, $minute = null, $second = null, $timezone = null) {
        $year = nativeToDatum($year);
        $month = nativeToDatum($month);
        $day = nativeToDatum($day);
        $hourOrTimezone = nativeToDatum($hourOrTimezone);
        if (isset($minute))
            $minute = nativeToDatum($minute);
        if (isset($second))
            $second = nativeToDatum($second);
        if (isset($timezone))
            $timezone = nativeToDatum($timezone);
        
        $this->setPositionalArg(0, $year);
        $this->setPositionalArg(1, $month);
        $this->setPositionalArg(2, $day);
        $this->setPositionalArg(3, $hourOrTimezone);
        if (isset($minute))
            $this->setPositionalArg(4, $minute);
        if (isset($second))
            $this->setPositionalArg(5, $second);
        if (isset($timezone))
            $this->setPositionalArg(6, $timezone);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_TIME;
    }
}

class EpochTime extends ValuedQuery
{
    public function __construct($epochTime) {
        $epochTime = nativeToDatum($epochTime);
        
        $this->setPositionalArg(0, $epochTime);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_EPOCH_TIME;
    }
}

class ToEpochTime extends ValuedQuery
{
    public function __construct(ValuedQuery $time) {
        $this->setPositionalArg(0, $time);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_TO_EPOCH_TIME;
    }
}

class Iso8601 extends ValuedQuery
{
    public function __construct($iso8601Date, $opts = null) {
        $iso8601Date = nativeToDatum($iso8601Date);
        
        $this->setPositionalArg(0, $iso8601Date);
        if (isset($opts)) {
            if (!is_array($opts)) throw new RqlDriverError("opts argument must be an array");
            foreach ($opts as $k => $v) {
                $this->setOptionalArg($k, nativeToDatum($v));
            }
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_ISO8601;
    }
}

class ToIso8601 extends ValuedQuery
{
    public function __construct(ValuedQuery $time) {
        $this->setPositionalArg(0, $time);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_TO_ISO8601;
    }
}

class InTimezone extends ValuedQuery
{
    public function __construct(ValuedQuery $time, $timezone) {
        $timezone = nativeToDatum($timezone);
            
        $this->setPositionalArg(0, $time);
        $this->setPositionalArg(1, $timezone);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_IN_TIMEZONE;
    }
}

class Timezone extends ValuedQuery
{
    public function __construct(ValuedQuery $time) {
        $this->setPositionalArg(0, $time);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_TIMEZONE;
    }
}

class During extends ValuedQuery
{
    public function __construct(ValuedQuery $time, $startTime, $endTime, $opts = null) {
        $startTime = nativeToDatum($startTime);
        $endTime = nativeToDatum($endTime);
            
        $this->setPositionalArg(0, $time);
        $this->setPositionalArg(1, $startTime);
        $this->setPositionalArg(2, $endTime);
        if (isset($opts)) {
            if (!is_array($opts)) throw new RqlDriverError("opts argument must be an array");
            foreach ($opts as $k => $v) {
                $this->setOptionalArg($k, nativeToDatum($v));
            }
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DURING;
    }
}

class Date extends ValuedQuery
{
    public function __construct(ValuedQuery $time) {
        $this->setPositionalArg(0, $time);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DATE;
    }
}

class TimeOfDay extends ValuedQuery
{
    public function __construct(ValuedQuery $time) {
        $this->setPositionalArg(0, $time);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_TIME_OF_DAY;
    }
}

class Year extends ValuedQuery
{
    public function __construct(ValuedQuery $time) {
        $this->setPositionalArg(0, $time);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_YEAR;
    }
}

class Month extends ValuedQuery
{
    public function __construct(ValuedQuery $time) {
        $this->setPositionalArg(0, $time);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_MONTH;
    }
}

class Day extends ValuedQuery
{
    public function __construct(ValuedQuery $time) {
        $this->setPositionalArg(0, $time);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DAY;
    }
}

class DayOfWeek extends ValuedQuery
{
    public function __construct(ValuedQuery $time) {
        $this->setPositionalArg(0, $time);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DAY_OF_WEEK;
    }
}

class DayOfYear extends ValuedQuery
{
    public function __construct(ValuedQuery $time) {
        $this->setPositionalArg(0, $time);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DAY_OF_YEAR;
    }
}

class Hours extends ValuedQuery
{
    public function __construct(ValuedQuery $time) {
        $this->setPositionalArg(0, $time);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_HOURS;
    }
}

class Minutes extends ValuedQuery
{
    public function __construct(ValuedQuery $time) {
        $this->setPositionalArg(0, $time);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_MINUTES;
    }
}

class Seconds extends ValuedQuery
{
    public function __construct(ValuedQuery $time) {
        $this->setPositionalArg(0, $time);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_SECONDS;
    }
}

// Constants
class Monday extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_MONDAY;
    }
}
class Tuesday extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_TUESDAY;
    }
}
class Wednesday extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_WEDNESDAY;
    }
}
class Thursday extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_THURSDAY;
    }
}
class Friday extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_FRIDAY;
    }
}
class Saturday extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_SATURDAY;
    }
}
class Sunday extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_SUNDAY;
    }
}

class January extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_JANUARY;
    }
}
class February extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_FEBRUARY;
    }
}
class March extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_MARCH;
    }
}
class April extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_APRIL;
    }
}
class May extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_MAY;
    }
}
class June extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_JUNE;
    }
}
class July extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_JULY;
    }
}
class August extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_AUGUST;
    }
}
class September extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_SEPTEMBER;
    }
}
class October extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_OCTOBER;
    }
}
class November extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_NOVEMBER;
    }
}
class December extends ValuedQuery {
    protected function getTermType() {
        return pb\Term_TermType::PB_DECEMBER;
    }
}

?>
