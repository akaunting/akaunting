<?php

namespace Money\Currencies;

use Money\Currencies;
use Money\Currency;
use Money\Exception\UnknownCurrencyException;

/**
 * Aggregates several currency repositories.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class AggregateCurrencies implements Currencies
{
    /**
     * @var Currencies[]
     */
    private $currencies;

    /**
     * @param Currencies[] $currencies
     */
    public function __construct(array $currencies)
    {
        foreach ($currencies as $c) {
            if (false === $c instanceof Currencies) {
                throw new \InvalidArgumentException('All currency repositories must implement '.Currencies::class);
            }
        }

        $this->currencies = $currencies;
    }

    /**
     * {@inheritdoc}
     */
    public function contains(Currency $currency)
    {
        foreach ($this->currencies as $currencies) {
            if ($currencies->contains($currency)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function subunitFor(Currency $currency)
    {
        foreach ($this->currencies as $currencies) {
            if ($currencies->contains($currency)) {
                return $currencies->subunitFor($currency);
            }
        }

        throw new UnknownCurrencyException('Cannot find currency '.$currency->getCode());
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $iterator = new \AppendIterator();

        foreach ($this->currencies as $currencies) {
            $iterator->append($currencies->getIterator());
        }

        return $iterator;
    }
}
