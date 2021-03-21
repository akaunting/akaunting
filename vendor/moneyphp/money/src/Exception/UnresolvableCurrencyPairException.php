<?php

namespace Money\Exception;

use Money\Currency;
use Money\Exception;

/**
 * Thrown when there is no currency pair (rate) available for the given currencies.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class UnresolvableCurrencyPairException extends \InvalidArgumentException implements Exception
{
    /**
     * Creates an exception from Currency objects.
     *
     * @param Currency $baseCurrency
     * @param Currency $counterCurrency
     *
     * @return UnresolvableCurrencyPairException
     */
    public static function createFromCurrencies(Currency $baseCurrency, Currency $counterCurrency)
    {
        $message = sprintf(
            'Cannot resolve a currency pair for currencies: %s/%s',
            $baseCurrency->getCode(),
            $counterCurrency->getCode()
        );

        return new self($message);
    }
}
