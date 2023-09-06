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
 * List of supported Crypto Currencies codes and names using Binance as resource.
 */
final class CryptoCurrencies implements Currencies
{
    /**
     * Map of known currencies indexed by code.
     *
     * @psalm-var non-empty-array<non-empty-string, array{
     *     symbol: non-empty-string,
     *     minorUnit: positive-int|0
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
     *     symbol: non-empty-string,
     *     minorUnit: positive-int|0
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
     *     symbol: non-empty-string,
     *     minorUnit: positive-int|0
     * }>
     *
     * @psalm-suppress MoreSpecificReturnType do not specify all keys and values
     */
    private function loadCurrencies(): array
    {
        $file = __DIR__ . '/../../resources/binance.php';

        if (is_file($file)) {
            /** @psalm-suppress LessSpecificReturnStatement */
            return require $file;
        }

        throw new RuntimeException('Failed to load currency ISO codes.');
    }
}
