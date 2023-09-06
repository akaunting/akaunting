<?php

namespace Bugsnag\DateTime;

use DateTimeImmutable;

final class Date
{
    /**
     * @return string
     */
    public static function now(ClockInterface $clock = null)
    {
        if ($clock === null) {
            $clock = new Clock();
        }

        $date = $clock->now();

        return self::format($date);
    }

    /**
     * @param DateTimeImmutable $date
     *
     * @return string
     */
    private static function format(DateTimeImmutable $date)
    {
        $dateTime = $date->format('Y-m-d\TH:i:s');

        // The milliseconds format character ("v") was introduced in PHP 7.0, so
        // we need to take microseconds (PHP 5.2+) and convert to milliseconds
        $microseconds = $date->format('u');
        $milliseconds = substr($microseconds, 0, 3);

        $offset = $date->format('P');

        return "{$dateTime}.{$milliseconds}{$offset}";
    }
}
