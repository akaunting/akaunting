<?php

namespace Bugsnag\DateTime;

use DateTimeImmutable;

final class Clock implements ClockInterface
{
    /**
     * @return DateTimeImmutable
     */
    public function now()
    {
        return new DateTimeImmutable();
    }
}
