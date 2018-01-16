<?php
namespace shaked\time;

/**
 * @author Shaked Klein Orbach <klein.shaked at gmail.com>
 */
class Sleep {
    /**
     * @param Duration $duration
     */
    public function for(Duration $duration) {
        switch ($duration->type()) {
        case Duration::Nanosecond:
            time_nanosleep(0, $duration->nanoseconds());
            break;
        case Duration::Microsecond:
            usleep($duration->microseconds());
            break;
        case Duration::Millisecond:
            usleep($duration->milliseconds());
            break;
        case Duration::Second:
            sleep($duration->seconds());
            break;
        case Duration::Minute:
            sleep($duration->seconds());
            break;
        case Duration::Hour:
            sleep($duration->seconds());
            break;
        }
    }

    /**
     * @param DateTime $dateTime
     */
    public static function until(DateTime $dateTime) {
        //TODO: think about http://php.net/manual/en/function.time-sleep-until.php
    }
}