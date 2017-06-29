<?php
require_once __DIR__ . '/../vendor/autoload.php';
use shaked\time\Duration;

class DurationTest extends PHPUnit_Framework_TestCase {
    public function testDurationString() {
        // Parity with https://play.golang.org/p/ZNwbZGKfAf
        $tests = [
            0 => '0s',

            Duration::Nanosecond       => '1ns',
            100 * Duration::Nanosecond => '100ns',

            Duration::Microsecond       => '1µs',
            100 * Duration::Microsecond => '100µs',

            Duration::Millisecond       => '1ms',
            100 * Duration::Millisecond => '100ms',

            Duration::Second      => '1s',
            30 * Duration::Second => '30s',
            60 * Duration::Second => '1m0s',

            1 * Duration::Minute  => '1m0s',
            10 * Duration::Minute => '10m0s',

            1 * Duration::Hour   => '1h0m0s',
            100 * Duration::Hour => '100h0m0s',

            100 * Duration::Hour + 10 * Duration::Minute + 5 * Duration::Second + 500 * Duration::Millisecond + 40000 * Duration::Microsecond + 3000000 * Duration::Nanosecond => '100h10m5.543s',
        ];
        foreach ($tests as $in => $expected) {
            $dur = Duration::nanosecond($in);
            $actual = $dur->__toString();
            $this->assertEquals($expected, $actual);
        }
    }
}