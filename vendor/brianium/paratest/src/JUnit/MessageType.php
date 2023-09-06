<?php

declare(strict_types=1);

namespace ParaTest\JUnit;

/** @internal */
enum MessageType
{
    case error;
    case failure;
    case skipped;

    public function toString(): string
    {
        return match ($this) {
            self::error => 'error',
            self::failure => 'failure',
            self::skipped => 'skipped',
        };
    }
}
