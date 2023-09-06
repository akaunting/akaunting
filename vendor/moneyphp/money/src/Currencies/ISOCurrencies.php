<?php

declare(strict_types=1);

namespace Money\Currencies;

use ArrayIterator;
use Money\Currencies;
use Money\Currency;
use Money\Exception\UnknownCurrencyException;
use RuntimeException;
use Traversable;

use function array_keys;
use function array_map;
use function is_file;

/**
 * List of supported ISO 4217 currency codes and names.
 */
final class ISOCurrencies implements Currencies
{
    /**
     * Map of known currencies indexed by code.
     *
     * @psalm-var non-empty-array<non-empty-string, array{
     *     alphabeticCode: non-empty-string,
     *     currency: non-empty-string,
     *     minorUnit: positive-int|0,
     *     numericCode: positive-int
     * }>|null
     */
    private static ?array $currencies = null;

    public function contains(Currency $currency): bool
    {
        return isset($this->getCurrencies()[$currency->getCode()]);
    }

    public function subunitFor(Currency $currency): int
    {
        if (! $this->contains($currency)) {
            throw new UnknownCurrencyException('Cannot find ISO currency ' . $currency->getCode());
        }

        return $this->getCurrencies()[$currency->getCode()]['minorUnit'];
    }

    /**
     * Returns the numeric code for a currency.
     *
     * @throws UnknownCurrencyException If currency is not available in the current context.
     */
    public function numericCodeFor(Currency $currency): int
    {
        if (! $this->contains($currency)) {
            throw new UnknownCurrencyException('Cannot find ISO currency ' . $currency->getCode());
        }

        return $this->getCurrencies()[$currency->getCode()]['numericCode'];
    }

    /**
     * @psalm-return Traversable<int, Currency>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator(
            array_map(
                static function ($code) {
                    return new Currency($code);
                },
                array_keys($this->getCurrencies())
            )
        );
    }

    /**
     * Returns a map of known currencies indexed by code.
     *
     * @psalm-return non-empty-array<non-empty-string, array{
     *     alphabeticCode: non-empty-string,
     *     currency: non-empty-string,
     *     minorUnit: positive-int|0,
     *     numericCode: positive-int
     * }>
     */
    private function getCurrencies(): array
    {
        if (self::$currencies === null) {
            self::$currencies = $this->loadCurrencies();
        }

        return self::$currencies;
    }

    /**
     * @psalm-return non-empty-array<non-empty-string, array{
     *     alphabeticCode: non-empty-string,
     *     currency: non-empty-string,
     *     minorUnit: positive-int|0,
     *     numericCode: positive-int
     * }>
     *
     * @psalm-suppress MoreSpecificReturnType do not specify all keys and values
     */
    private function loadCurrencies(): array
    {
        $file = __DIR__ . '/../../resources/currency.php';

        if (is_file($file)) {
            /** @psalm-suppress LessSpecificReturnStatement */
            return require $file;
        }

        throw new RuntimeException('Failed to load currency ISO codes.');
    }
}
