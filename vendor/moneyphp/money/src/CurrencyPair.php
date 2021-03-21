<?php

namespace Money;

/**
 * Currency Pair holding a base, a counter currency and a conversion ratio.
 *
 * @author Mathias Verraes
 *
 * @see http://en.wikipedia.org/wiki/Currency_pair
 */
final class CurrencyPair implements \JsonSerializable
{
    /**
     * Currency to convert from.
     *
     * @var Currency
     */
    private $baseCurrency;

    /**
     * Currency to convert to.
     *
     * @var Currency
     */
    private $counterCurrency;

    /**
     * @var float
     */
    private $conversionRatio;

    /**
     * @param Currency $baseCurrency
     * @param Currency $counterCurrency
     * @param float    $conversionRatio
     *
     * @throws \InvalidArgumentException If conversion ratio is not numeric
     */
    public function __construct(Currency $baseCurrency, Currency $counterCurrency, $conversionRatio)
    {
        if (!is_numeric($conversionRatio)) {
            throw new \InvalidArgumentException('Conversion ratio must be numeric');
        }

        $this->counterCurrency = $counterCurrency;
        $this->baseCurrency = $baseCurrency;
        $this->conversionRatio = (float) $conversionRatio;
    }

    /**
     * Creates a new Currency Pair based on "EUR/USD 1.2500" form representation.
     *
     * @param string $iso String representation of the form "EUR/USD 1.2500"
     *
     * @return CurrencyPair
     *
     * @throws \InvalidArgumentException Format of $iso is invalid
     */
    public static function createFromIso($iso)
    {
        $currency = '([A-Z]{2,3})';
        $ratio = "([0-9]*\.?[0-9]+)"; // @see http://www.regular-expressions.info/floatingpoint.html
        $pattern = '#'.$currency.'/'.$currency.' '.$ratio.'#';

        $matches = [];

        if (!preg_match($pattern, $iso, $matches)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cannot create currency pair from ISO string "%s", format of string is invalid',
                    $iso
                )
            );
        }

        return new self(new Currency($matches[1]), new Currency($matches[2]), $matches[3]);
    }

    /**
     * Returns the counter currency.
     *
     * @return Currency
     */
    public function getCounterCurrency()
    {
        return $this->counterCurrency;
    }

    /**
     * Returns the base currency.
     *
     * @return Currency
     */
    public function getBaseCurrency()
    {
        return $this->baseCurrency;
    }

    /**
     * Returns the conversion ratio.
     *
     * @return float
     */
    public function getConversionRatio()
    {
        return $this->conversionRatio;
    }

    /**
     * Checks if an other CurrencyPair has the same parameters as this.
     *
     * @param CurrencyPair $other
     *
     * @return bool
     */
    public function equals(CurrencyPair $other)
    {
        return
            $this->baseCurrency->equals($other->baseCurrency)
            && $this->counterCurrency->equals($other->counterCurrency)
            && $this->conversionRatio === $other->conversionRatio
        ;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'baseCurrency' => $this->baseCurrency,
            'counterCurrency' => $this->counterCurrency,
            'ratio' => $this->conversionRatio,
        ];
    }
}
