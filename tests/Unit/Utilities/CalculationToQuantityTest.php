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

    /**
     * @dataProvider validExpressions
     */
    public function testItEvaluatesExpressionsLikePhp(string $expression, int|float $expected): void
    {
        $this->assertSame($expected, calculation_to_quantity($expression));
    }

    public static function validExpressions(): array
    {
        return [
            ['3', 3],
            ['2.5', 2.5],
            ['.5', 0.5],
            ['2 x 3', 6],
            ['2*3+4', 10],
            ['2+3*4', 14],
            ['(2+3)*4', 20],
            ['10/4', 2.5],
            ['8/2/2', 2],
            ['10-2-3', 5],
            ['-2+5', 3],
            ['2 + +2', 4],
            ['2*-3', -6],
            ['2**3', 8],
            ['-2**2', -4],
            ['2**3**2', 512],
            [' 1,5 x 2 ', 3.0],
        ];
    }

    /**
     * @dataProvider invalidExpressions
     */
    public function testItRejectsInvalidExpressions(string $expression): void
    {
        $this->expectException(InvalidArgumentException::class);

        calculation_to_quantity($expression);
    }

    public static function invalidExpressions(): array
    {
        return [
            [''],
            ['2--2'],
            ['2*'],
            ['(2+3'],
            ['2+3)'],
            ['()'],
            ['1.2.3'],
            ['1/0'],
            ['phpinfo()'],
            ['system(1)'],
            [str_repeat('(', 40) . '1' . str_repeat(')', 40)],
            [str_repeat('1+', 200) . '1'],
        ];
    }
}
