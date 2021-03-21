<?php

namespace Money\PHPUnit;

use Money\Currencies\AggregateCurrencies;
use Money\Currencies\BitcoinCurrencies;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * The comparator is for comparing Money objects in PHPUnit tests.
 *
 * Add this to your bootstrap file:
 *
 * \SebastianBergmann\Comparator\Factory::getInstance()->register(new \Money\PHPUnit\Comparator());
 */
final class Comparator extends \SebastianBergmann\Comparator\Comparator
{
    /**
     * @var IntlMoneyFormatter
     */
    private $formatter;

    public function __construct()
    {
        parent::__construct();

        $currencies = new AggregateCurrencies([
            new ISOCurrencies(),
            new BitcoinCurrencies(),
        ]);

        $numberFormatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        $this->formatter = new IntlMoneyFormatter($numberFormatter, $currencies);
    }

    public function accepts($expected, $actual)
    {
        return $expected instanceof Money && $actual instanceof Money;
    }

    /**
     * @param Money $expected
     * @param Money $actual
     * @param float $delta
     * @param bool  $canonicalize
     * @param bool  $ignoreCase
     * @param array $processed
     */
    public function assertEquals(
        $expected,
        $actual,
        $delta = 0.0,
        $canonicalize = false,
        $ignoreCase = false,
        array &$processed = []
    ) {
        if (!$expected->equals($actual)) {
            throw new ComparisonFailure(
                $expected,
                $actual,
                $this->formatter->format($expected),
                $this->formatter->format($actual),
                false,
                'Failed asserting that two Money objects are equal.'
            );
        }
    }
}
