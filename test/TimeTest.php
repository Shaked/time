<?php
require_once __DIR__ . '/../vendor/autoload.php';
use shaked\time\Duration;
use shaked\time\Sleep;

class TimeTest extends PHPUnit_Framework_TestCase {
    /**
     * @var int
     */
    private $n = 60;

    public function setUp() {
        $this->durations = [
            'nanosecond'  => Duration::nanosecond($this->n),
            'microsecond' => Duration::microsecond($this->n),
            'millisecond' => Duration::millisecond($this->n),
            'second'      => Duration::second($this->n),
            'minute'      => Duration::minute($this->n),
            'hour'        => Duration::hour($this->n),
        ];
        $this->expectedDurations = [
            'nanosecond'  => [
                'nanoseconds'  => 60,
                'microseconds' => 0.06,
                'milliseconds' => 6e-05,
                'seconds'      => 6.0E-8,
                'minutes'      => 1.0E-9,
                'hours'        => 1.6666666666667E-11,
            ],
            'microsecond' => [
                'nanoseconds'  => 60000,
                'microseconds' => 60,
                'milliseconds' => 0.06,
                'seconds'      => 6e-05,
                'minutes'      => 1e-06,
                'hours'        => 1.6666666666666667e-08,
            ],
            'millisecond' => [
                'nanoseconds'  => 60000000,
                'microseconds' => 60000,
                'milliseconds' => 60,
                'seconds'      => 0.060000000000000005,
                'minutes'      => 0.001,
                'hours'        => 1.6666666666666667e-05,
            ],
            'second'      => [
                'nanoseconds'  => 60000000000,
                'microseconds' => 6e+07,
                'milliseconds' => 60000,
                'seconds'      => 60,
                'minutes'      => 1,
                'hours'        => 0.016666666666666666,
            ],
            'minute'      => [
                'nanoseconds'  => 3600000000000,
                'microseconds' => 3.6e+09,
                'milliseconds' => 3.6e+06,
                'seconds'      => 3600,
                'minutes'      => 60,
                'hours'        => 1,
            ],
            'hour'        => [
                'nanoseconds'  => 216000000000000,
                'microseconds' => 2.16e+11,
                'milliseconds' => 2.16e+08,
                'seconds'      => 216000,
                'minutes'      => 3600,
                'hours'        => 60,
            ],
        ];
    }

    public function testDuration() {
        foreach ($this->durations as $durationName => $duration) {
            foreach ($this->expectedDurations[$durationName] as $expectedDurationName => $expectedDuration) {
                $actual = $duration->$expectedDurationName();
                $expected = $expectedDuration;
                $this->assertEquals($actual, $expected);
            }
        }
    }

    public function testSleep() {
        $sleep = $this->createMock(Sleep::class);
        $sleep->method('for')->will($this->returnCallback(function (Duration $duration) {
            return [
                'minutes' => $duration->minutes(),
                'type'    => $duration->type(),
            ];
        }));
        $n = 60;

        foreach ($this->durations as $durationName => $duration) {
            $for = $sleep->for($duration);
            $actual = $for['minutes'];
            $expected = $this->expectedDurations[$durationName]['minutes'];
            $this->assertEquals($actual, $expected);
        }
    }
}