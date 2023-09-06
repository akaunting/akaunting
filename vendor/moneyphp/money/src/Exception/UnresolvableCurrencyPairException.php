<?php

declare(strict_types=1);

namespace Money\Exception;

use InvalidArgumentException;
use Money\Currency;
use Money\Exception;

use function sprintf;

/**
 * Thrown when there is no currency pair (rate) available for the given currencies.
 */
final class UnresolvableCurrencyPairException extends InvalidArgumentException implements Exception
{
    /**
     * Creates an exception from Currency objects.
     */
    public static function createFromCurrencies(Currency $baseCurrency, Currency $counterCurrency): UnresolvableCurrencyPairException
    {
        $message = sprintf(
            'Cannot resolve a currency pair for currencies: %s/%s',
            $baseCurrency->getCode(),
            $counterCurrency->getCode()
        );

        return new self($message);
    }
}
