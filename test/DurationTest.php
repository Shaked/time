<?php
// TODO: use PHPUnit_Framework_TestCase instead
require_once __DIR__ . '/../vendor/autoload.php';
use Shaked\Time\Duration;
use Shaked\Time\Sleep;

/**
 * @param string $name
 * @return mixed
 */
function c(string $name) {
    $opt = getopt('d::');

    $c = [
        'debug' => $opt['d'] ?? false,
    ];

    if (!array_key_exists($name, $c)) {
        return null;
    }

    return $c[$name];
}
/**
 * @return null
 */
function println() {
    if (!c('debug')) {
        return;
    }
    $args = func_get_args();
    $text = '';
    foreach ($args as $arg) {
        $text .= $arg . ' ';
    }
    echo $text . PHP_EOL;
}

/**
 * @param $actual
 * @param $expected
 */
function assertFloats($actual, $expected) {
    $delta = 0.0000000001;
    $abs = abs($actual - $expected);
    $asseration = !($abs > $delta);
    $msg = sprintf("Actual: %s.\n Expected: %s.\n Absolute subtraction: %s", $actual, $expected, $abs);
    assert($asseration, $msg);
}
$tests = 0;
$n = 60;
$durations = [
    'nanosecond'  => Duration::nanosecond($n),
    'microsecond' => Duration::microsecond($n),
    'millisecond' => Duration::millisecond($n),
    'second'      => Duration::second($n),
    'minute'      => Duration::minute($n),
    'hour'        => Duration::hour($n),
];
$expectedDurations = [
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

foreach ($durations as $durationName => $duration) {
    foreach ($expectedDurations[$durationName] as $expectedDurationName => $expectedDuration) {
        $actual = $duration->$expectedDurationName();
        $expected = $expectedDuration;
        assertFloats($actual, $expected);
        $tests++;
    }
}
println('Testing Sleep class');
class SleepMock extends Sleep {
    public function for(Duration $duration) {
        return [
            'minutes' => $duration->minutes(),
            'type'    => $duration->type(),
        ];
    }
}
$sleep = new SleepMock();
$n = 60;

foreach ($durations as $durationName => $duration) {
    $for = $sleep->for($duration);
    $actual = $for['minutes'];
    $expected = $expectedDurations[$durationName]['minutes'];
    assertFloats($actual, $expected);
    $tests++;
    $now = microtime(true);
    $end = microtime(true) - $now;
    println('duration:', $for['minutes'], $for['type'], $end);
}

println('Finished successfully. Number of tests:', $tests);
exit(0);