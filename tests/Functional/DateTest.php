<?php

namespace r\Tests\Functional;

use DateTime;
use r\Tests\TestCase;

// use function \r\expr;
// use function \r\time as rTime;
// use function \r\epochTime;
// use function \r\iso8601;
// use function \r\now;
// use function \r\sunday;
// use function \r\monday;
// use function \r\tuesday;
// use function \r\wednesday;
// use function \r\thursday;
// use function \r\friday;
// use function \r\saturday;
// use function \r\january;
// use function \r\february;
// use function \r\march;
// use function \r\april;
// use function \r\may;
// use function \r\june;
// use function \r\july;
// use function \r\august;
// use function \r\september;
// use function \r\october;
// use function \r\november;
// use function \r\december;

class DateTest extends TestCase
{
    public function testTimeDate()
    {
        $this->assertTrue(
            \r\now()->sub(
                \r\time(floatval(date("Y")), floatval(date("m")), floatval(date("d")), date("P"))
            )->lt(24*60*60 + 10)
            ->run($this->conn)
        );
    }

    public function testTimeTime()
    {
        $this->assertTrue(
            \r\now()->sub(
                \r\time(
                    floatval(date("Y")),
                    floatval(date("m")),
                    floatval(date("d")),
                    floatval(date("H")),
                    floatval(date("i")),
                    floatval(date("s")),
                    date("P")
                )
            )->lt(24*60*60 + 10)
            ->run($this->conn)
        );
    }

    public function testEpochTimeSub()
    {
        $this->assertTrue(\r\now()->sub(\r\epochTime(time()))->lt(10)->run($this->conn));
    }

    public function testToEpochTime()
    {
        $this->assertTrue(\r\now()->toEpochTime()->sub(time())->lt(10)->run($this->conn));
    }

    public function testIso8601Sub()
    {
        $this->assertTrue(\r\now()->sub(\r\iso8601(date('c')))->lt(10)->run($this->conn));
    }

    public function testToIso8601()
    {
        $this->assertEquals(
            date('c', 111111),
            \r\iso8601(date('c', 111111))->toIso8601()->run($this->conn)
        );
    }

    public function testToIso8601DefaultTZ()
    {
        $this->assertEquals(
            date('c', 1),
            \r\iso8601('1970-01-01T00:00:01+00:00', array('default_timezone' => '+00:00'))
                ->toIso8601()->run($this->conn)
        );
    }

    public function testToIso8601InTZ()
    {
        $this->assertEquals(
            23.0,
            \r\time(2000, 1, 1, 0, 0, 0, '+00:00')
                ->inTimezone('-01:00')
                ->hours()
                ->run($this->conn)
        );
    }

    public function testTimeTimezone()
    {
        $this->assertEquals(
            '+00:00',
            \r\time(2000, 1, 1, 0, 0, 0, '+00:00')->timezone()->run($this->conn)
        );
    }

    public function testDurringPast()
    {
        $this->assertFalse(
            \r\now()->during(\r\now()->sub(10), \r\now()->sub(5))->run($this->conn)
        );
    }

    public function testDurringPresent()
    {
        $this->assertTrue(
            \r\now()->during(\r\now()->sub(10), \r\now()->add(10))->run($this->conn)
        );
    }

    public function testDurringFuture()
    {
        $this->assertFalse(
            \r\now()->during(\r\now()->add(10), \r\now()->add(10))->run($this->conn)
        );
    }

    public function testDurringEpochNowAndFuture()
    {
        $this->assertTrue(
            \r\epochTime(111111)->during(
                \r\epochTime(111111),
                \r\epochTime(111111)->add(10)
            )->run($this->conn)
        );
    }

    public function testDurringEpochPastAndNow()
    {
        $this->assertFalse(
            \r\epochTime(111111)->during(
                \r\epochTime(111111)->sub(10),
                \r\epochTime(111111)
            )->run($this->conn)
        );
    }

    public function testDurringEpochNowAndFutureLeftBound()
    {
        $this->assertFalse(
            \r\epochTime(111111)->during(
                \r\epochTime(111111),
                \r\epochTime(111111)->add(10),
                array('left_bound' => 'open')
            )->run($this->conn)
        );
    }

    public function testDurringEpochRightBoundClosed()
    {
        $this->assertTrue(
            \r\epochTime(111111)->during(
                \r\epochTime(111111)->sub(10),
                \r\epochTime(111111),
                array('right_bound' => 'closed')
            )->run($this->conn)
        );
    }

    public function testEpochDateHours()
    {
        $this->assertEquals(0.0, \r\epochTime(111111)->date()->hours()->run($this->conn));
    }

    public function testEpochDateYears()
    {
        $this->assertEquals(1970.0, \r\epochTime(111111)->date()->year()->run($this->conn));

    }

    public function testEpochTimeOfDay()
    {
        $this->assertEquals(24711.0, \r\epochTime(111111)->timeOfDay()->run($this->conn));

    }

    public function testEpochYear()
    {
        $this->assertEquals(1970.0, \r\epochTime(111111)->year()->run($this->conn));

    }

    public function testEpochMonth()
    {
        $this->assertEquals(1.0, \r\epochTime(111111)->month()->run($this->conn));

    }

    public function testEpochDay()
    {
        $this->assertEquals(2.0, \r\epochTime(111111)->day()->run($this->conn));

    }

    public function testEpochDayofWeek()
    {
        $this->assertEquals(5.0, \r\epochTime(111111)->dayOfWeek()->run($this->conn));

    }

    public function testEpochDayofYear()
    {
        $this->assertEquals(2.0, \r\epochTime(111111)->dayOfYear()->run($this->conn));

    }

    public function testEpochHours()
    {
        $this->assertEquals(6.0, \r\epochTime(111111)->hours()->run($this->conn));

    }

    public function testEpochMinutes()
    {
        $this->assertEquals(51.0, \r\epochTime(111111)->minutes()->run($this->conn));

    }

    public function testEpochSeconds()
    {
        $this->assertEquals(50.0, \r\epochTime(111110)->seconds()->run($this->conn));

    }

    public function testMonday()
    {
        $this->assertEquals(1.0, \r\monday()->run($this->conn));

    }

    public function testTuesday()
    {
        $this->assertEquals(2.0, \r\tuesday()->run($this->conn));

    }

    public function testWednesday()
    {
        $this->assertEquals(3.0, \r\wednesday()->run($this->conn));
    }

    public function testThursday()
    {
        $this->assertEquals(4.0, \r\thursday()->run($this->conn));
    }

    public function testFriday()
    {
        $this->assertEquals(5.0, \r\friday()->run($this->conn));
    }

    public function testSaturday()
    {
        $this->assertEquals(6.0, \r\saturday()->run($this->conn));
    }

    public function testSunday()
    {
        $this->assertEquals(7.0, \r\sunday()->run($this->conn));
    }

    public function testJanuary()
    {
        $this->assertEquals(1.0, \r\january()->run($this->conn));
    }

    public function testFebruary()
    {
        $this->assertEquals(2.0, \r\february()->run($this->conn));
    }

    public function testMarch()
    {
        $this->assertEquals(3.0, \r\march()->run($this->conn));
    }

    public function testApril()
    {
        $this->assertEquals(4.0, \r\april()->run($this->conn));
    }

    public function testMay()
    {
        $this->assertEquals(5.0, \r\may()->run($this->conn));
    }

    public function testJune()
    {
        $this->assertEquals(6.0, \r\june()->run($this->conn));
    }

    public function testJuly()
    {
        $this->assertEquals(7.0, \r\july()->run($this->conn));
    }

    public function testAugust()
    {
        $this->assertEquals(8.0, \r\august()->run($this->conn));
    }

    public function testSeptember()
    {
        $this->assertEquals(9.0, \r\september()->run($this->conn));
    }

    public function testOctober()
    {
        $this->assertEquals(10.0, \r\october()->run($this->conn));
    }

    public function testNovember()
    {
        $this->assertEquals(11.0, \r\november()->run($this->conn));
    }

    public function testDecember()
    {
        $this->assertEquals(12.0, \r\december()->run($this->conn));
    }

    public function testExptime()
    {
        $this->assertEquals(
            'PTYPE<TIME>',
            \r\expr(new DateTime('2000-01-02'))->typeOf()->run($this->conn)
        );
    }

    public function testExprYear()
    {
        $this->assertEquals(
            2000.0,
            \r\expr(new DateTime('2000-01-02'))->year()->run($this->conn)
        );
    }

    public function testExprMonth()
    {
        $this->assertEquals(
            1.0,
            \r\expr(new DateTime('2000-01-02'))->month()->run($this->conn)
        );
    }

    public function testExprDay()
    {
        $this->assertEquals(
            2.0,
            \r\expr(new DateTime('2000-01-02'))->day()->run($this->conn)
        );
    }

    public function testTime()
    {
        $this->assertEquals(
            new DateTime('1969-01-01 -0000'),
            \r\time(1969, 1, 1, 0, 0, 0, "+00:00")->run($this->conn)
        );
    }

    public function testNegativeTime()
    {
        $this->assertEquals(
            new DateTime('2000-01-01 -0000'),
            \r\time(2000, 1, 1, 0, 0, 0, "+00:00")->run($this->conn)
        );
    }

    public function testEpochTime()
    {
        $this->assertEquals(
            new DateTime('1970-01-02 06:51:51 -0000'),
            \r\epochTime(111111)->run($this->conn)
        );
    }

    public function testIso8601()
    {
        $this->assertEquals(
            new DateTime("1997-07-16T19:20:30.453+01:00"),
            \r\iso8601("1997-07-16T19:20:30.453+01:00")->run($this->conn)
        );
    }

    public function testTimeNative()
    {
        $this->assertEquals(
            new DateTime('2000-01-01 -0000'),
            \r\time(2000, 1, 1, 0, 0, 0, "+00:00")
                ->run($this->conn, array('timeFormat' => 'native'))
        );
    }

    public function testTimeRaw()
    {
        $this->assertEquals(
            array(
                '$reql_type$' => 'TIME',
                'epoch_time' => 946684800.0,
                'timezone' => "+00:00"
            ),
            (array)\r\time(2000, 1, 1, 0, 0, 0, '+00:00')
                ->run($this->conn, array('timeFormat' => 'raw'))
        );
    }

    public function testTimeCDT()
    {
        $this->assertEquals(
            new DateTime('2000-01-01 05:45:32 CDT'),
            \r\time(2000, 1, 1, 5, 45, 32, "-05:00")
                ->run($this->conn, array('timeFormat' => 'native'))
        );
    }
}
