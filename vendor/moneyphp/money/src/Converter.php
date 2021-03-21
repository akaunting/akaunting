<?php

namespace Money;

/**
 * Provides a way to convert Money to Money in another Currency using an exchange rate.
 *
 * @author Frederik Bosch <f.bosch@genkgo.nl>
 */
final class Converter
{
    /**
     * @var Currencies
     */
    private $currencies;

    /**
     * @var Exchange
     */
    private $exchange;

    /**
     * @param Currencies $currencies
     * @param Exchange   $exchange
     */
    public function __construct(Currencies $currencies, Exchange $exchange)
    {
        $this->currencies = $currencies;
        $this->exchange = $exchange;
    }

    /**
     * @param Money    $money
     * @param Currency $counterCurrency
     * @param int      $roundingMode
     *
     * @return Money
     */
    public function convert(Money $money, Currency $counterCurrency, $roundingMode = Money::ROUND_HALF_UP)
    {
        $baseCurrency = $money->getCurrency();
        $ratio = $this->exchange->quote($baseCurrency, $counterCurrency)->getConversionRatio();

        $baseCurrencySubunit = $this->currencies->subunitFor($baseCurrency);
        $counterCurrencySubunit = $this->currencies->subunitFor($counterCurrency);
        $subunitDifference = $baseCurrencySubunit - $counterCurrencySubunit;

        $ratio = (string) Number::fromFloat($ratio)->base10($subunitDifference);

        $counterValue = $money->multiply($ratio, $roundingMode);

        return new Money($counterValue->getAmount(), $counterCurrency);
    }
}
