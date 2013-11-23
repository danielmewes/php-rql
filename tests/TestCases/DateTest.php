<?php

class DateTest extends TestCase
{
    public function run()
    {
        date_default_timezone_set('America/Los_Angeles');

        $this->checkQueryResult(r\now()->sub(r\time(floatval(date("Y")), floatval(date("m")), floatval(date("d")), date("P")))->lt(24*60*60 + 10), true);
        $this->checkQueryResult(r\now()->sub(r\time(floatval(date("Y")), floatval(date("m")), floatval(date("d")), floatval(date("H")), floatval(date("i")), floatval(date("s")), date("P")))->lt(10), true);
        $this->checkQueryResult(r\now()->sub(r\epochTime(time()))->lt(10), true);
        $this->checkQueryResult(r\now()->toEpochTime()->sub(time())->lt(10), true);
        $this->checkQueryResult(r\now()->sub(r\iso8601(date("c")))->lt(10), true);
        $this->checkQueryResult(r\now()->sub(r\iso8601(date("c")))->lt(10), true);
        $this->checkQueryResult(r\iso8601(date("c", 111111))->toIso8601(), date("c", 111111));
        $this->checkQueryResult(r\iso8601("1970-01-01T22:51:51", array('default_timezone' => "-08:00"))->toIso8601(), date("c", 111111));
        $this->checkQueryResult(r\time(2000, 1, 1, 0, 0, 0, "+00:00")->inTimezone("-01:00")->hours(), 23.0);
        $this->checkQueryResult(r\time(2000, 1, 1, 0, 0, 0, "+00:00")->timezone(), "+00:00");
        $this->checkQueryResult(r\now()->during(r\now()->sub(10), r\now()->sub(5)), false);
        $this->checkQueryResult(r\now()->during(r\now()->sub(10), r\now()->add(10)), true);
        $this->checkQueryResult(r\now()->during(r\now()->add(5), r\now()->add(10)), false);
        $this->checkQueryResult(r\epochTime(111111)->during(r\epochTime(111111), r\epochTime(111111)->add(10)), true);
        $this->checkQueryResult(r\epochTime(111111)->during(r\epochTime(111111)->sub(10), r\epochTime(111111)), false);
        $this->checkQueryResult(r\epochTime(111111)->during(r\epochTime(111111), r\epochTime(111111)->add(10), array('left_bound' => "open")), false);
        $this->checkQueryResult(r\epochTime(111111)->during(r\epochTime(111111)->sub(10), r\epochTime(111111), array('right_bound' => "closed")), true);
        $this->checkQueryResult(r\epochTime(111111)->date()->hours(), 0.0);
        $this->checkQueryResult(r\epochTime(111111)->date()->year(), 1970.0);
        $this->checkQueryResult(r\epochTime(111111)->timeOfDay(), 24711.0);
        $this->checkQueryResult(r\epochTime(111111)->year(), 1970.0);
        $this->checkQueryResult(r\epochTime(111111)->month(), 1.0);
        $this->checkQueryResult(r\epochTime(111111)->day(), 2.0);
        $this->checkQueryResult(r\epochTime(111111)->dayOfWeek(), 5.0);
        $this->checkQueryResult(r\epochTime(111111)->dayOfYear(), 2.0);
        $this->checkQueryResult(r\epochTime(111111)->hours(), 6.0);
        $this->checkQueryResult(r\epochTime(111111)->minutes(), 51.0);
        $this->checkQueryResult(r\epochTime(111110)->seconds(), 50.0);

        $this->checkQueryResult(r\monday(), 1.0);
        $this->checkQueryResult(r\tuesday(), 2.0);
        $this->checkQueryResult(r\wednesday(), 3.0);
        $this->checkQueryResult(r\thursday(), 4.0);
        $this->checkQueryResult(r\friday(), 5.0);
        $this->checkQueryResult(r\saturday(), 6.0);
        $this->checkQueryResult(r\sunday(), 7.0);

        $this->checkQueryResult(r\january(), 1.0);
        $this->checkQueryResult(r\february(), 2.0);
        $this->checkQueryResult(r\march(), 3.0);
        $this->checkQueryResult(r\april(), 4.0);
        $this->checkQueryResult(r\may(), 5.0);
        $this->checkQueryResult(r\june(), 6.0);
        $this->checkQueryResult(r\july(), 7.0);
        $this->checkQueryResult(r\august(), 8.0);
        $this->checkQueryResult(r\september(), 9.0);
        $this->checkQueryResult(r\october(), 10.0);
        $this->checkQueryResult(r\november(), 11.0);
        $this->checkQueryResult(r\december(), 12.0);
    }
}

?>
