<?php

require __DIR__.'/../vendor/autoload.php';

use Money\Currencies;
use Money\Currency;

(static function (): void {
    $buffer = <<<'PHP'
<?php

declare(strict_types=1);

namespace Money;

use InvalidArgumentException;

/**
 * This is a generated file. Do not edit it manually!
 *
PHPDOC
 * @psalm-immutable
 */
trait MoneyFactory
{
    /**
     * Convenience factory method for a Money object.
     *
     * <code>
     * $fiveDollar = Money::USD(500);
     * </code>
     *
     * @param array $arguments
     * @psalm-param non-empty-string          $method
     * @psalm-param array{numeric-string|int} $arguments
     *
     * @throws InvalidArgumentException If amount is not integer(ish).
     *
     * @psalm-pure
     */
    public static function __callStatic(string $method, array $arguments): Money
    {
        return new Money($arguments[0], new Currency($method));
    }
}

PHP;

    $methodBuffer = '';

    $iterator = new Currencies\AggregateCurrencies([
        new Currencies\ISOCurrencies(),
        new Currencies\BitcoinCurrencies(),
        new Currencies\CryptoCurrencies(),
    ]);

    $currencies = array_unique([...$iterator]);
    usort($currencies, static fn (Currency $a, Currency $b): int => strcmp($a->getCode(), $b->getCode()));

    /** @var Currency[] $currencies */
    foreach ($currencies as $currency) {
        $code = $currency->getCode();
        if (is_numeric($code[0])) {
            preg_match('/^([0-9]*)(.*?)$/', $code, $extracted);

            $formatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
            $code = strtoupper(preg_replace('/\s+/', '', $formatter->format($extracted[1])) . $extracted[2]);
        }

        $methodBuffer .= sprintf(" * @method static Money %s(numeric-string|int \$amount)\n", $code);
    }

    $buffer = str_replace('PHPDOC', rtrim($methodBuffer), $buffer);

    file_put_contents(__DIR__.'/../src/MoneyFactory.php', $buffer);
})();
