<?php

require_once 'vendor/autoload.php';

use shaked\time\Duration;
use shaked\time\Sleep;

$n = 60;

$durations = [
    'nanosecond'  => Duration::nanosecond($n),
    'microsecond' => Duration::microsecond($n),
    'millisecond' => Duration::millisecond($n),
    'second'      => Duration::second($n),
    'minute'      => Duration::minute($n),
    'hour'        => Duration::hour($n),
];

foreach ($durations as $duration) {
    (new Sleep())->for($duration);
    echo (string) $duration . PHP_EOL;
}

// 60ns
// 60µs
// 60ms
// 1m0s
// 1h0m0s
// 60h0m0s
