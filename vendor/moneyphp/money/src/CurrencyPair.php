<?php

declare(strict_types=1);

namespace Money;

use InvalidArgumentException;
use JsonSerializable;

use function assert;
use function is_numeric;
use function preg_match;
use function sprintf;

/**
 * Currency Pair holding a base, a counter currency and a conversion ratio.
 *
 * @see http://en.wikipedia.org/wiki/Currency_pair
 */
final class CurrencyPair implements JsonSerializable
{
    /**
     * Currency to convert from.
     */
    private Currency $baseCurrency;

    /**
     * Currency to convert to.
     */
    private Currency $counterCurrency;

    /** @psalm-var numeric-string */
    private string $conversionRatio;

    /**
     * @psalm-param numeric-string $conversionRatio
     */
    public function __construct(Currency $baseCurrency, Currency $counterCurrency, string $conversionRatio)
    {
        $this->counterCurrency = $counterCurrency;
        $this->baseCurrency    = $baseCurrency;
        $this->conversionRatio = $conversionRatio;
    }

    /**
     * Creates a new Currency Pair based on "EUR/USD 1.2500" form representation.
     *
     * @param string $iso String representation of the form "EUR/USD 1.2500"
     *
     * @throws InvalidArgumentException Format of $iso is invalid.
     */
    public static function createFromIso(string $iso): CurrencyPair
    {
        $currency = '([A-Z]{2,3})';
        $ratio    = '([0-9]*\.?[0-9]+)'; // @see http://www.regular-expressions.info/floatingpoint.html
        $pattern  = '#' . $currency . '/' . $currency . ' ' . $ratio . '#';

        $matches = [];

        if (! preg_match($pattern, $iso, $matches)) {
            throw new InvalidArgumentException(sprintf('Cannot create currency pair from ISO string "%s", format of string is invalid', $iso));
        }

        assert($matches[1] !== '');
        assert($matches[2] !== '');
        assert(is_numeric($matches[3]));

        return new self(new Currency($matches[1]), new Currency($matches[2]), $matches[3]);
    }

    /**
     * Returns the counter currency.
     */
    public function getCounterCurrency(): Currency
    {
        return $this->counterCurrency;
    }

    /**
     * Returns the base currency.
     */
    public function getBaseCurrency(): Currency
    {
        return $this->baseCurrency;
    }

    /**
     * Returns the conversion ratio.
     *
     * @psalm-return numeric-string
     */
    public function getConversionRatio(): string
    {
        return $this->conversionRatio;
    }

    /**
     * Checks if an other CurrencyPair has the same parameters as this.
     */
    public function equals(CurrencyPair $other): bool
    {
        return $this->baseCurrency->equals($other->baseCurrency)
            && $this->counterCurrency->equals($other->counterCurrency)
            && $this->conversionRatio === $other->conversionRatio;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-return array{baseCurrency: Currency, counterCurrency: Currency, ratio: numeric-string}
     */
    public function jsonSerialize(): array
    {
        return [
            'baseCurrency' => $this->baseCurrency,
            'counterCurrency' => $this->counterCurrency,
            'ratio' => $this->conversionRatio,
        ];
    }
}
