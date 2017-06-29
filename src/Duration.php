<?php
namespace shaked\time;

/**
 * @author Shaked Klein Orbach <klein.shaked at gmail.com>
 */
class Duration {
    const MAX = PHP_INT_MAX;
    const MIN = -PHP_INT_MAX;

    const Nanosecond = 1;
    /**
     * 1000 * self::Nanosecond
     */
    const Microsecond = 1000;
    /**
     * 1000 * self::Microsecond;
     */
    const Millisecond = 1000000;
    /**
     * 1000 * self::Millisecond;
     */
    const Second = 1000000000;
    /**
     * 60 * self::Second;
     */
    const Minute = 60000000000;
    /**
     * 60 * self::Minute;
     */
    const Hour = 3600000000000;

    private $n;

    /**
     * @param $n
     */
    private function __construct($n, $type) {
        $this->n = $n * $type;
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function type() {
        return $this->type;
    }

    /**
     * @param $n
     */
    public static function nanosecond($n) {
        return new self($n, self::Nanosecond);
    }

    /**
     * @param $n
     */
    public static function microsecond($n) {
        return new self($n, self::Microsecond);
    }

    /**
     * @param $n
     */
    public static function millisecond($n) {
        return new self($n, self::Millisecond);
    }

    /**
     * @param $n
     */
    public static function second($n) {
        return new self($n, self::Second);
    }

    /**
     * @param $n
     */
    public static function minute($n) {
        return new self($n, self::Minute);
    }

    /**
     * @param $n
     */
    public static function hour($n) {
        return new self($n, self::Hour);
    }

    public function nanoseconds(): int {
        return (int) ($this->n);
    }

    public function microseconds(): float {
        return (float) ($this->nanoseconds()) / self::Microsecond;
    }

    public function milliseconds(): float {
        return (float) ($this->nanoseconds()) / self::Millisecond;
    }

    /**
     * @return mixed
     */
    public function seconds(): float {
        $sec = (int) ($this->n / self::Second);
        $nsec = $this->n % self::Second;
        return (double) ($sec) + (double) ($nsec) * 1e-9;
    }

    /**
     * @return mixed
     */
    public function minutes(): float {
        $min = (int) ($this->n / self::Minute);
        $nsec = $this->n % self::Minute;
        return (double) ($min) + (double) ($nsec) * (1e-9 / 60);
    }

    /**
     * @return mixed
     */
    public function hours(): float {
        $hour = (int) ($this->n / self::Hour);
        $nsec = $this->n % self::Hour;
        return (double) ($hour) + (double) ($nsec) * (1e-9 / 60 / 60);
    }

    // String returns a string representing the duration in the form "72h3m0.5s".
    // Leading zero units are omitted. As a special case, durations less than one
    // second format use a smaller unit (milli-, micro-, or nanoseconds) to ensure
    // that the leading digit is non-zero. The zero duration formats as 0s.
    /**
     * @return mixed
     */
    public function __toString() {
        if ($this->n == 0) {
            return '0s';
        }

        $u = $this->n;
        $buf = '';

        if ($u < self::Second) {
            if ($u < self::Microsecond) {
                $buf = $u . 'ns';
            } elseif ($u < self::Millisecond) {
                $buf = (float) sprintf('%.3f', $u / self::Microsecond);
                $buf .= 'µs';
            } else {
                $buf = (float) sprintf('%.6f', $u / self::Millisecond);
                $buf .= 'ms';
            }

            return $buf;
        }

        $dimensions = [
            self::Hour   => 'h',
            self::Minute => 'm',
        ];

        $print = false;
        foreach ($dimensions as $dur => $suffix) {
            if ($u >= $dur) {
                $d = intdiv($u, $dur);
                $buf .= $d;
                $buf .= $suffix;
                $u = fmod($u, $dur);
                $print = true;
            } elseif ($print) {
                $buf .= '0' . $suffix;
            }
        }

        $s = (float) sprintf('%0.9f', $u / self::Second);
        $buf .= $s . 's';

        return $buf;
    }

}