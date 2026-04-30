<?php

namespace Tests\Unit\Utilities;

use App\Utilities\Date;
use PHPUnit\Framework\TestCase;

class DateImportParsingTest extends TestCase
{
    public function testItParsesLocalizedMonthNamesUsingFallbackLocale(): void
    {
        $this->assertSame(
            '2025-12-22',
            Date::parseWithFallbackLocales('22 Des 2025', null, ['id'])->format('Y-m-d')
        );
    }

    public function testItParsesIsoStringsWithoutLocaleTranslation(): void
    {
        $this->assertSame(
            '2025-12-22 00:00:00',
            Date::parseWithFallbackLocales('2025-12-22 00:00:00')->format('Y-m-d H:i:s')
        );
    }
}
