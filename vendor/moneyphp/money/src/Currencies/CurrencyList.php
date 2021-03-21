<?php

namespace Money\Currencies;

use Money\Currencies;
use Money\Currency;
use Money\Exception\UnknownCurrencyException;

/**
 * A list of custom currencies.
 *
 * @author George Mponos <gmponos@gmail.com>
 */
final class CurrencyList implements Currencies
{
    /**
     * Map of currencies indexed by code.
     *
     * @var array
     */
    private $currencies;

    public function __construct(array $currencies)
    {
        foreach ($currencies as $currencyCode => $subunit) {
            if (empty($currencyCode) || !is_string($currencyCode)) {
                throw new \InvalidArgumentException(
                    sprintf('Currency code must be a string and not empty. "%s" given', $currencyCode)
                );
            }

            if (!is_int($subunit) || $subunit < 0) {
                throw new \InvalidArgumentException(
                    sprintf('Currency %s does not have a valid minor unit. Must be a positive integer.', $currencyCode)
                );
            }
        }

        $this->currencies = $currencies;
    }

    /**
     * {@inheritdoc}
     */
    public function contains(Currency $currency)
    {
        return isset($this->currencies[$currency->getCode()]);
    }

    /**
     * {@inheritdoc}
     */
    public function subunitFor(Currency $currency)
    {
        if (!$this->contains($currency)) {
            throw new UnknownCurrencyException('Cannot find currency '.$currency->getCode());
        }

        return $this->currencies[$currency->getCode()];
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator(
            array_map(
                function ($code) {
                    return new Currency($code);
                },
                array_keys($this->currencies)
            )
        );
    }
}
