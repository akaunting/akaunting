<?php

namespace Bugsnag\DateTime;

use DateTimeImmutable;

interface ClockInterface
{
    /**
     * @return DateTimeImmutable
     */
    public function now();
}
