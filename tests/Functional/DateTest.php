<?php

namespace r\Tests\Functional;

use DateTime;
 use function \r\expr;
 use function \r\time as rTime;
 use function \r\epochTime;
 use function \r\iso8601;
 use function \r\now;
 use function \r\sunday;
 use function \r\monday;
 use function \r\tuesday;
 use function \r\wednesday;
 use function \r\thursday;
 use function \r\friday;
 use function \r\saturday;
 use function \r\january;
 use function \r\february;
 use function \r\march;
 use function \r\april;
 use function \r\may;
 use function \r\june;
 use function \r\july;
 use function \r\august;
 use function \r\september;
 use function \r\october;
 use function \r\november;
 use function \r\december;
use r\Tests\TestCase;

class DateTest extends TestCase
{
    public function testTimeDate()
    {
        $this->assertTrue(
            now()
                ->sub(
                    rTime(floatval(date('Y')), floatval(date('m')), floatval(date('d')), date('P'))
                )->lt(24 * 60 * 60 + 10)
                ->run($this->conn)
        );
    }

    public function testTimeTime()
    {
        $this->assertTrue(now()->sub(rTime(floatval(date('Y')), floatval(date('m')), floatval(date('d')), floatval(date('H')), floatval(date('i')), floatval(date('s')), date('P')))->lt(24 * 60 * 60 + 10)->run($this->conn));
    }

    public function testEpochTimeSub()
    {
        $this->assertTrue(now()->sub(epochTime(time()))->lt(10)->run($this->conn));
    }

    public function testToEpochTime()
    {
        $this->assertTrue(now()->toEpochTime()->sub(time())->lt(10)->run($this->conn));
    }

    public function testIso8601Sub()
    {
        $this->assertTrue(now()->sub(iso8601(date('c')))->lt(10)->run($this->conn));
    }

    public function testToIso8601()
    {
        $this->assertEquals(date('c', 111111), iso8601(date('c', 111111))->toIso8601()->run($this->conn));
    }

    public function testToIso8601DefaultTZ()
    {
        $this->assertEquals(date('c', 1), iso8601('1970-01-01T00:00:01+00:00', ['default_timezone' => '+00:00'])->toIso8601()->run($this->conn));
    }

    public function testToIso8601InTZ()
    {
        $this->assertEquals(23.0, rTime(2000, 1, 1, 0, 0, 0, '+00:00')->inTimezone('-01:00')->hours()->run($this->conn));
    }

    public function testTimeTimezone()
    {
        $this->assertEquals('+00:00', rTime(2000, 1, 1, 0, 0, 0, '+00:00')->timezone()->run($this->conn));
    }

    public function testDurringPast()
    {
        $this->assertFalse(now()->during(now()->sub(10), now()->sub(5))->run($this->conn));
    }

    public function testDurringPresent()
    {
        $this->assertTrue(now()->during(now()->sub(10), now()->add(10))->run($this->conn));
    }

    public function testDurringFuture()
    {
        $this->assertFalse(now()->during(now()->add(10), now()->add(10))->run($this->conn));
    }

    public function testDurringEpochNowAndFuture()
    {
        $this->assertTrue(epochTime(111111)->during(epochTime(111111), epochTime(111111)->add(10))->run($this->conn));
    }

    public function testDurringEpochPastAndNow()
    {
        $this->assertFalse(epochTime(111111)->during(epochTime(111111)->sub(10), epochTime(111111))->run($this->conn));
    }

    public function testDurringEpochNowAndFutureLeftBound()
    {
        $this->assertFalse(epochTime(111111)->during(epochTime(111111), epochTime(111111)->add(10), ['left_bound' => 'open'])->run($this->conn));
    }

    public function testDurringEpochRightBoundClosed()
    {
        $this->assertTrue(epochTime(111111)->during(epochTime(111111)->sub(10), epochTime(111111), ['right_bound' => 'closed'])->run($this->conn));
    }

    public function testEpochDateHours()
    {
        $this->assertEquals(0.0, epochTime(111111)->date()->hours()->run($this->conn));
    }

    public function testEpochDateYears()
    {
        $this->assertEquals(1970.0, epochTime(111111)->date()->year()->run($this->conn));
    }

    public function testEpochTimeOfDay()
    {
        $this->assertEquals(24711.0, epochTime(111111)->timeOfDay()->run($this->conn));
    }

    public function testEpochYear()
    {
        $this->assertEquals(1970.0, epochTime(111111)->year()->run($this->conn));
    }

    public function testEpochMonth()
    {
        $this->assertEquals(1.0, epochTime(111111)->month()->run($this->conn));
    }

    public function testEpochDay()
    {
        $this->assertEquals(2.0, epochTime(111111)->day()->run($this->conn));
    }

    public function testEpochDayofWeek()
    {
        $this->assertEquals(5.0, epochTime(111111)->dayOfWeek()->run($this->conn));
    }

    public function testEpochDayofYear()
    {
        $this->assertEquals(2.0, epochTime(111111)->dayOfYear()->run($this->conn));
    }

    public function testEpochHours()
    {
        $this->assertEquals(6.0, epochTime(111111)->hours()->run($this->conn));
    }

    public function testEpochMinutes()
    {
        $this->assertEquals(51.0, epochTime(111111)->minutes()->run($this->conn));
    }

    public function testEpochSeconds()
    {
        $this->assertEquals(50.0, epochTime(111110)->seconds()->run($this->conn));
    }

    public function testMonday()
    {
        $this->assertEquals(1.0, monday()->run($this->conn));
    }

    public function testTuesday()
    {
        $this->assertEquals(2.0, tuesday()->run($this->conn));
    }

    public function testWednesday()
    {
        $this->assertEquals(3.0, wednesday()->run($this->conn));
    }

    public function testThursday()
    {
        $this->assertEquals(4.0, thursday()->run($this->conn));
    }

    public function testFriday()
    {
        $this->assertEquals(5.0, friday()->run($this->conn));
    }

    public function testSaturday()
    {
        $this->assertEquals(6.0, saturday()->run($this->conn));
    }

    public function testSunday()
    {
        $this->assertEquals(7.0, sunday()->run($this->conn));
    }

    public function testJanuary()
    {
        $this->assertEquals(1.0, january()->run($this->conn));
    }

    public function testFebruary()
    {
        $this->assertEquals(2.0, february()->run($this->conn));
    }

    public function testMarch()
    {
        $this->assertEquals(3.0, march()->run($this->conn));
    }

    public function testApril()
    {
        $this->assertEquals(4.0, april()->run($this->conn));
    }

    public function testMay()
    {
        $this->assertEquals(5.0, may()->run($this->conn));
    }

    public function testJune()
    {
        $this->assertEquals(6.0, june()->run($this->conn));
    }

    public function testJuly()
    {
        $this->assertEquals(7.0, july()->run($this->conn));
    }

    public function testAugust()
    {
        $this->assertEquals(8.0, august()->run($this->conn));
    }

    public function testSeptember()
    {
        $this->assertEquals(9.0, september()->run($this->conn));
    }

    public function testOctober()
    {
        $this->assertEquals(10.0, october()->run($this->conn));
    }

    public function testNovember()
    {
        $this->assertEquals(11.0, november()->run($this->conn));
    }

    public function testDecember()
    {
        $this->assertEquals(12.0, december()->run($this->conn));
    }

    public function testExptime()
    {
        $this->assertEquals('PTYPE<TIME>', expr(new DateTime('2000-01-02'))->typeOf()->run($this->conn));
    }

    public function testExprYear()
    {
        $this->assertEquals(2000.0, expr(new DateTime('2000-01-02'))->year()->run($this->conn));
    }

    public function testExprMonth()
    {
        $this->assertEquals(1.0, expr(new DateTime('2000-01-02'))->month()->run($this->conn));
    }

    public function testExprDay()
    {
        $this->assertEquals(2.0, expr(new DateTime('2000-01-02'))->day()->run($this->conn));
    }

    public function testTime()
    {
        $this->assertEquals(new DateTime('1969-01-01 -0000'), rTime(1969, 1, 1, 0, 0, 0, '+00:00')->run($this->conn));
    }

    public function testNegativeTime()
    {
        $this->assertEquals(new DateTime('2000-01-01 -0000'), rTime(2000, 1, 1, 0, 0, 0, '+00:00')->run($this->conn));
    }

    public function testEpochTime()
    {
        $this->assertEquals(new DateTime('1970-01-02 06:51:51 -0000'), epochTime(111111)->run($this->conn));
    }

    public function testIso8601()
    {
        $this->assertEquals(new DateTime('1997-07-16T19:20:30.453+01:00'), iso8601('1997-07-16T19:20:30.453+01:00')->run($this->conn));
    }

    public function testTimeNative()
    {
        $this->assertEquals(new DateTime('2000-01-01 -0000'), rTime(2000, 1, 1, 0, 0, 0, '+00:00')->run($this->conn, ['timeFormat' => 'native']));
    }

    public function testTimeRaw()
    {
        $this->assertEquals(['$reql_type$' => 'TIME', 'epoch_time' => 946684800.0, 'timezone' => '+00:00'], (array) rTime(2000, 1, 1, 0, 0, 0, '+00:00')->run($this->conn, ['timeFormat' => 'raw']));
    }

    public function testTimeCDT()
    {
        $this->assertEquals(new DateTime('2000-01-01 05:45:32 CDT'), rTime(2000, 1, 1, 5, 45, 32, '-05:00')->run($this->conn, ['timeFormat' => 'native']));
    }
}
