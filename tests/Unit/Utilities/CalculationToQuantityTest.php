<?php

namespace Tests\Unit\Utilities;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CalculationToQuantityTest extends TestCase
{
    public function testItAcceptsCommaDecimalQuantities(): void
    {
        $this->assertSame(1.5, calculation_to_quantity('1,5'));
    }

    public function testItThrowsForInvalidMathematicalExpressions(): void
    {
        $this->expectException(InvalidArgumentException::class);

        calculation_to_quantity('2++2');
    }
}
