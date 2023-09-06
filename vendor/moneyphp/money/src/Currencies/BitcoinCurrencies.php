<?php

declare(strict_types=1);

namespace Money\Currencies;

use ArrayIterator;
use Money\Currencies;
use Money\Currency;
use Money\Exception\UnknownCurrencyException;
use Traversable;

final class BitcoinCurrencies implements Currencies
{
    public const CODE = 'XBT';

    public const SYMBOL = "\xC9\x83";

    public function contains(Currency $currency): bool
    {
        return $currency->getCode() === self::CODE;
    }

    public function subunitFor(Currency $currency): int
    {
        if ($currency->getCode() !== self::CODE) {
            throw new UnknownCurrencyException($currency->getCode() . ' is not bitcoin and is not supported by this currency repository');
        }

        return 8;
    }

    /** {@inheritDoc} */
    public function getIterator(): Traversable
    {
        return new ArrayIterator([new Currency(self::CODE)]);
    }
}
