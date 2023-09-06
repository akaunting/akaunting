<?php

declare(strict_types=1);

namespace ZipStream\Test;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ZipStream\Exception\DosTimeOverflowException;
use ZipStream\Time;

class TimeTest extends TestCase
{
    public function testNormalDateToDosTime(): void
    {
        $this->assertSame(
            Time::dateTimeToDosTime(new DateTimeImmutable('2014-11-17T17:46:08Z')),
            1165069764
        );

        // January 1 1980 - DOS Epoch.
        $this->assertSame(
            Time::dateTimeToDosTime(new DateTimeImmutable('1980-01-01T00:00:00+00:00')),
            2162688
        );
    }

    public function testTooEarlyDateToDosTime(): void
    {
        $this->expectException(DosTimeOverflowException::class);

        // January 1 1980 is the minimum DOS Epoch.
        Time::dateTimeToDosTime(new DateTimeImmutable('1970-01-01T00:00:00+00:00'));
    }
}
