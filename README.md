# PHP time package

[![Build Status](https://travis-ci.org/Shaked/time.svg?branch=master)](https://travis-ci.org/Shaked/time)

The purpose of this package is to help developers to write a more readable PHP code while being able to easily test it.

The code is very much influenced by the [Go time package](https://golang.org/pkg/time/).

## Usage

@see [DurationTest](test/DurationTest.php) for more information.

```
$n = 60;

$durations = [
    'nanosecond'  => Duration::nanosecond($n),
    'microsecond' => Duration::microsecond($n),
    'millisecond' => Duration::millisecond($n),
    'second'      => Duration::second($n),
    'minute'      => Duration::minute($n),
    'hour'        => Duration::hour($n),
];

foreach($durations as $duration) {
    (new Sleep())->for($duration);
}
```

