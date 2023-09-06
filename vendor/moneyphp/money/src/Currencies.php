<?php

declare(strict_types=1);

namespace Money;

use IteratorAggregate;
use Money\Exception\UnknownCurrencyException;
use Traversable;

/**
 * Implement this to provide a list of currencies.
 */
interface Currencies extends IteratorAggregate
{
    /**
     * Checks whether a currency is available in the current context.
     */
    public function contains(Currency $currency): bool;

    /**
     * Returns the subunit for a currency.
     *
     * @throws UnknownCurrencyException If currency is not available in the current context.
     */
    public function subunitFor(Currency $currency): int;

    /**
     * @psalm-return Traversable<int|string, Currency>
     */
    public function getIterator(): Traversable;
}
