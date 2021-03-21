<?php

namespace Money;

use Money\Exception\UnknownCurrencyException;

/**
 * Implement this to provide a list of currencies.
 *
 * @author Mathias Verraes
 */
interface Currencies extends \IteratorAggregate
{
    /**
     * Checks whether a currency is available in the current context.
     *
     * @param Currency $currency
     *
     * @return bool
     */
    public function contains(Currency $currency);

    /**
     * Returns the subunit for a currency.
     *
     * @param Currency $currency
     *
     * @return int
     *
     * @throws UnknownCurrencyException If currency is not available in the current context
     */
    public function subunitFor(Currency $currency);
}
