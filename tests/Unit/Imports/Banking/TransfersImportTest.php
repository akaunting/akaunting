<?php

namespace Tests\Unit\Imports\Banking;

use App\Imports\Banking\Transfers;
use PHPUnit\Framework\TestCase;

class TransfersImportTest extends TestCase
{
    public function testItKeepsNormalizedTransferredAtWithoutReParsing(): void
    {
        $import = new class() extends Transfers {
            public function __construct()
            {
            }

            public function normalizeForTest($value): ?string
            {
                return $this->normalizeTransferredAt($value);
            }
        };

        $this->assertSame('2025-12-22', $import->normalizeForTest('2025-12-22 00:00:00'));
        $this->assertSame('2025-12-22', $import->normalizeForTest('2025-12-22'));
    }
}
