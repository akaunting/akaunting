<?php

declare(strict_types=1);

namespace Money;

use Money\Exception\UnresolvableCurrencyPairException;

/**
 * Provides a way to get exchange rate from a third-party source and return a currency pair.
 */
interface Exchange
{
    /**
     * Returns a currency pair for the passed currencies with the rate coming from a third-party source.
     *
     * @throws UnresolvableCurrencyPairException When there is no currency pair (rate) available for the given currencies.
     */
    public function quote(Currency $baseCurrency, Currency $counterCurrency): CurrencyPair;
}
