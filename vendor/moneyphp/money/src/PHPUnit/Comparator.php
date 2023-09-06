<?php

declare(strict_types=1);

namespace Money\PHPUnit;

use Money\Currencies\AggregateCurrencies;
use Money\Currencies\BitcoinCurrencies;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;
use SebastianBergmann\Comparator\ComparisonFailure;

use function assert;

/**
 * The comparator is for comparing Money objects in PHPUnit tests.
 *
 * Add this to your bootstrap file:
 *
 * \SebastianBergmann\Comparator\Factory::getInstance()->register(new \Money\PHPUnit\Comparator());
 *
 * @internal do not use within your sources: this comparator is only to be used within the test suite of this library
 *
 * @psalm-suppress PropertyNotSetInConstructor the parent implementation includes factories that cannot be initialized here
 */
final class Comparator extends \SebastianBergmann\Comparator\Comparator
{
    private IntlMoneyFormatter $formatter;

    public function __construct()
    {
        parent::__construct();

        $currencies = new AggregateCurrencies([
            new ISOCurrencies(),
            new BitcoinCurrencies(),
        ]);

        $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
        $this->formatter = new IntlMoneyFormatter($numberFormatter, $currencies);
    }

    /** {@inheritDoc} */
    public function accepts($expected, $actual)
    {
        return $expected instanceof Money && $actual instanceof Money;
    }

    /** {@inheritDoc} */
    public function assertEquals(
        $expected,
        $actual,
        $delta = 0.0,
        $canonicalize = false,
        $ignoreCase = false
    ): void {
        assert($expected instanceof Money);
        assert($actual instanceof Money);

        if (! $expected->equals($actual)) {
            throw new ComparisonFailure($expected, $actual, $this->formatter->format($expected), $this->formatter->format($actual), false, 'Failed asserting that two Money objects are equal.');
        }
    }
}
