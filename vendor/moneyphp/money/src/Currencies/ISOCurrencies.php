<?php

namespace Money\Currencies;

use Money\Currencies;
use Money\Currency;
use Money\Exception\UnknownCurrencyException;

/**
 * List of supported ISO 4217 currency codes and names.
 *
 * @author Mathias Verraes
 */
final class ISOCurrencies implements Currencies
{
    /**
     * Map of known currencies indexed by code.
     *
     * @var array
     */
    private static $currencies;

    /**
     * {@inheritdoc}
     */
    public function contains(Currency $currency)
    {
        return isset($this->getCurrencies()[$currency->getCode()]);
    }

    /**
     * {@inheritdoc}
     */
    public function subunitFor(Currency $currency)
    {
        if (!$this->contains($currency)) {
            throw new UnknownCurrencyException('Cannot find ISO currency '.$currency->getCode());
        }

        return $this->getCurrencies()[$currency->getCode()]['minorUnit'];
    }

    /**
     * Returns the numeric code for a currency.
     *
     * @param Currency $currency
     *
     * @return int
     *
     * @throws UnknownCurrencyException If currency is not available in the current context
     */
    public function numericCodeFor(Currency $currency)
    {
        if (!$this->contains($currency)) {
            throw new UnknownCurrencyException('Cannot find ISO currency '.$currency->getCode());
        }

        return $this->getCurrencies()[$currency->getCode()]['numericCode'];
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator(
            array_map(
                function ($code) {
                    return new Currency($code);
                },
                array_keys($this->getCurrencies())
            )
        );
    }

    /**
     * Returns a map of known currencies indexed by code.
     *
     * @return array
     */
    private function getCurrencies()
    {
        if (null === self::$currencies) {
            self::$currencies = $this->loadCurrencies();
        }

        return self::$currencies;
    }

    /**
     * @return array
     */
    private function loadCurrencies()
    {
        $file = __DIR__.'/../../resources/currency.php';

        if (file_exists($file)) {
            return require $file;
        }

        throw new \RuntimeException('Failed to load currency ISO codes.');
    }
}
