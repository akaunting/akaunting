<?php

declare(strict_types=1);

namespace Money\Exchange;

use Money\Currency;
use Money\CurrencyPair;
use Money\Exception\UnresolvableCurrencyPairException;
use Money\Exchange;
use Money\Money;

/**
 * Tries the reverse of the currency pair if one is not available.
 *
 * Note: adding nested ReversedCurrenciesExchange could cause a huge performance hit.
 */
final class ReversedCurrenciesExchange implements Exchange
{
    private Exchange $exchange;

    public function __construct(Exchange $exchange)
    {
        $this->exchange = $exchange;
    }

    public function quote(Currency $baseCurrency, Currency $counterCurrency): CurrencyPair
    {
        try {
            return $this->exchange->quote($baseCurrency, $counterCurrency);
        } catch (UnresolvableCurrencyPairException $exception) {
            $calculator = Money::getCalculator();

            try {
                $currencyPair = $this->exchange->quote($counterCurrency, $baseCurrency);

                return new CurrencyPair(
                    $baseCurrency,
                    $counterCurrency,
                    $calculator::divide('1', $currencyPair->getConversionRatio())
                );
            } catch (UnresolvableCurrencyPairException) {
                throw $exception;
            }
        }
    }
}
