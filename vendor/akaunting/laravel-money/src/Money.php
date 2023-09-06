<?php

namespace Akaunting\Money;

use Akaunting\Money\Casts\MoneyCast;
use Akaunting\Money\Exceptions\UnexpectedAmountException;
use BadFunctionCallException;
use Closure;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use JsonSerializable;
use OutOfBoundsException;

/**
 * Class Money.
 *
 * @method static Money AED(mixed $amount, bool $convert = false)
 * @method static Money AFN(mixed $amount, bool $convert = false)
 * @method static Money ALL(mixed $amount, bool $convert = false)
 * @method static Money AMD(mixed $amount, bool $convert = false)
 * @method static Money ANG(mixed $amount, bool $convert = false)
 * @method static Money AOA(mixed $amount, bool $convert = false)
 * @method static Money ARS(mixed $amount, bool $convert = false)
 * @method static Money AUD(mixed $amount, bool $convert = false)
 * @method static Money AWG(mixed $amount, bool $convert = false)
 * @method static Money AZN(mixed $amount, bool $convert = false)
 * @method static Money BAM(mixed $amount, bool $convert = false)
 * @method static Money BBD(mixed $amount, bool $convert = false)
 * @method static Money BDT(mixed $amount, bool $convert = false)
 * @method static Money BGN(mixed $amount, bool $convert = false)
 * @method static Money BHD(mixed $amount, bool $convert = false)
 * @method static Money BIF(mixed $amount, bool $convert = false)
 * @method static Money BMD(mixed $amount, bool $convert = false)
 * @method static Money BND(mixed $amount, bool $convert = false)
 * @method static Money BOB(mixed $amount, bool $convert = false)
 * @method static Money BOV(mixed $amount, bool $convert = false)
 * @method static Money BRL(mixed $amount, bool $convert = false)
 * @method static Money BSD(mixed $amount, bool $convert = false)
 * @method static Money BTN(mixed $amount, bool $convert = false)
 * @method static Money BWP(mixed $amount, bool $convert = false)
 * @method static Money BYR(mixed $amount, bool $convert = false)
 * @method static Money BZD(mixed $amount, bool $convert = false)
 * @method static Money CAD(mixed $amount, bool $convert = false)
 * @method static Money CDF(mixed $amount, bool $convert = false)
 * @method static Money CHF(mixed $amount, bool $convert = false)
 * @method static Money CLF(mixed $amount, bool $convert = false)
 * @method static Money CLP(mixed $amount, bool $convert = false)
 * @method static Money CNY(mixed $amount, bool $convert = false)
 * @method static Money COP(mixed $amount, bool $convert = false)
 * @method static Money CRC(mixed $amount, bool $convert = false)
 * @method static Money CUC(mixed $amount, bool $convert = false)
 * @method static Money CUP(mixed $amount, bool $convert = false)
 * @method static Money CVE(mixed $amount, bool $convert = false)
 * @method static Money CZK(mixed $amount, bool $convert = false)
 * @method static Money DJF(mixed $amount, bool $convert = false)
 * @method static Money DKK(mixed $amount, bool $convert = false)
 * @method static Money DOP(mixed $amount, bool $convert = false)
 * @method static Money DZD(mixed $amount, bool $convert = false)
 * @method static Money EGP(mixed $amount, bool $convert = false)
 * @method static Money ERN(mixed $amount, bool $convert = false)
 * @method static Money ETB(mixed $amount, bool $convert = false)
 * @method static Money EUR(mixed $amount, bool $convert = false)
 * @method static Money FJD(mixed $amount, bool $convert = false)
 * @method static Money FKP(mixed $amount, bool $convert = false)
 * @method static Money GBP(mixed $amount, bool $convert = false)
 * @method static Money GEL(mixed $amount, bool $convert = false)
 * @method static Money GHS(mixed $amount, bool $convert = false)
 * @method static Money GIP(mixed $amount, bool $convert = false)
 * @method static Money GMD(mixed $amount, bool $convert = false)
 * @method static Money GNF(mixed $amount, bool $convert = false)
 * @method static Money GTQ(mixed $amount, bool $convert = false)
 * @method static Money GYD(mixed $amount, bool $convert = false)
 * @method static Money HKD(mixed $amount, bool $convert = false)
 * @method static Money HNL(mixed $amount, bool $convert = false)
 * @method static Money HRK(mixed $amount, bool $convert = false)
 * @method static Money HTG(mixed $amount, bool $convert = false)
 * @method static Money HUF(mixed $amount, bool $convert = false)
 * @method static Money IDR(mixed $amount, bool $convert = false)
 * @method static Money ILS(mixed $amount, bool $convert = false)
 * @method static Money INR(mixed $amount, bool $convert = false)
 * @method static Money IQD(mixed $amount, bool $convert = false)
 * @method static Money IRR(mixed $amount, bool $convert = false)
 * @method static Money ISK(mixed $amount, bool $convert = false)
 * @method static Money JMD(mixed $amount, bool $convert = false)
 * @method static Money JOD(mixed $amount, bool $convert = false)
 * @method static Money JPY(mixed $amount, bool $convert = false)
 * @method static Money KES(mixed $amount, bool $convert = false)
 * @method static Money KGS(mixed $amount, bool $convert = false)
 * @method static Money KHR(mixed $amount, bool $convert = false)
 * @method static Money KMF(mixed $amount, bool $convert = false)
 * @method static Money KPW(mixed $amount, bool $convert = false)
 * @method static Money KRW(mixed $amount, bool $convert = false)
 * @method static Money KWD(mixed $amount, bool $convert = false)
 * @method static Money KYD(mixed $amount, bool $convert = false)
 * @method static Money KZT(mixed $amount, bool $convert = false)
 * @method static Money LAK(mixed $amount, bool $convert = false)
 * @method static Money LBP(mixed $amount, bool $convert = false)
 * @method static Money LKR(mixed $amount, bool $convert = false)
 * @method static Money LRD(mixed $amount, bool $convert = false)
 * @method static Money LSL(mixed $amount, bool $convert = false)
 * @method static Money LTL(mixed $amount, bool $convert = false)
 * @method static Money LVL(mixed $amount, bool $convert = false)
 * @method static Money LYD(mixed $amount, bool $convert = false)
 * @method static Money MAD(mixed $amount, bool $convert = false)
 * @method static Money MDL(mixed $amount, bool $convert = false)
 * @method static Money MGA(mixed $amount, bool $convert = false)
 * @method static Money MKD(mixed $amount, bool $convert = false)
 * @method static Money MMK(mixed $amount, bool $convert = false)
 * @method static Money MNT(mixed $amount, bool $convert = false)
 * @method static Money MOP(mixed $amount, bool $convert = false)
 * @method static Money MRO(mixed $amount, bool $convert = false)
 * @method static Money MUR(mixed $amount, bool $convert = false)
 * @method static Money MVR(mixed $amount, bool $convert = false)
 * @method static Money MWK(mixed $amount, bool $convert = false)
 * @method static Money MXN(mixed $amount, bool $convert = false)
 * @method static Money MYR(mixed $amount, bool $convert = false)
 * @method static Money MZN(mixed $amount, bool $convert = false)
 * @method static Money NAD(mixed $amount, bool $convert = false)
 * @method static Money NGN(mixed $amount, bool $convert = false)
 * @method static Money NIO(mixed $amount, bool $convert = false)
 * @method static Money NOK(mixed $amount, bool $convert = false)
 * @method static Money NPR(mixed $amount, bool $convert = false)
 * @method static Money NZD(mixed $amount, bool $convert = false)
 * @method static Money OMR(mixed $amount, bool $convert = false)
 * @method static Money PAB(mixed $amount, bool $convert = false)
 * @method static Money PEN(mixed $amount, bool $convert = false)
 * @method static Money PGK(mixed $amount, bool $convert = false)
 * @method static Money PHP(mixed $amount, bool $convert = false)
 * @method static Money PKR(mixed $amount, bool $convert = false)
 * @method static Money PLN(mixed $amount, bool $convert = false)
 * @method static Money PYG(mixed $amount, bool $convert = false)
 * @method static Money QAR(mixed $amount, bool $convert = false)
 * @method static Money RON(mixed $amount, bool $convert = false)
 * @method static Money RSD(mixed $amount, bool $convert = false)
 * @method static Money RUB(mixed $amount, bool $convert = false)
 * @method static Money RWF(mixed $amount, bool $convert = false)
 * @method static Money SAR(mixed $amount, bool $convert = false)
 * @method static Money SBD(mixed $amount, bool $convert = false)
 * @method static Money SCR(mixed $amount, bool $convert = false)
 * @method static Money SDG(mixed $amount, bool $convert = false)
 * @method static Money SEK(mixed $amount, bool $convert = false)
 * @method static Money SGD(mixed $amount, bool $convert = false)
 * @method static Money SHP(mixed $amount, bool $convert = false)
 * @method static Money SLL(mixed $amount, bool $convert = false)
 * @method static Money SOS(mixed $amount, bool $convert = false)
 * @method static Money SRD(mixed $amount, bool $convert = false)
 * @method static Money SSP(mixed $amount, bool $convert = false)
 * @method static Money STD(mixed $amount, bool $convert = false)
 * @method static Money SVC(mixed $amount, bool $convert = false)
 * @method static Money SYP(mixed $amount, bool $convert = false)
 * @method static Money SZL(mixed $amount, bool $convert = false)
 * @method static Money THB(mixed $amount, bool $convert = false)
 * @method static Money TJS(mixed $amount, bool $convert = false)
 * @method static Money TMT(mixed $amount, bool $convert = false)
 * @method static Money TND(mixed $amount, bool $convert = false)
 * @method static Money TOP(mixed $amount, bool $convert = false)
 * @method static Money TRY(mixed $amount, bool $convert = false)
 * @method static Money TTD(mixed $amount, bool $convert = false)
 * @method static Money TWD(mixed $amount, bool $convert = false)
 * @method static Money TZS(mixed $amount, bool $convert = false)
 * @method static Money UAH(mixed $amount, bool $convert = false)
 * @method static Money UGX(mixed $amount, bool $convert = false)
 * @method static Money USD(mixed $amount, bool $convert = false)
 * @method static Money UYU(mixed $amount, bool $convert = false)
 * @method static Money UZS(mixed $amount, bool $convert = false)
 * @method static Money VEF(mixed $amount, bool $convert = false)
 * @method static Money VND(mixed $amount, bool $convert = false)
 * @method static Money VUV(mixed $amount, bool $convert = false)
 * @method static Money WST(mixed $amount, bool $convert = false)
 * @method static Money XAF(mixed $amount, bool $convert = false)
 * @method static Money XAG(mixed $amount, bool $convert = false)
 * @method static Money XAU(mixed $amount, bool $convert = false)
 * @method static Money XCD(mixed $amount, bool $convert = false)
 * @method static Money XDR(mixed $amount, bool $convert = false)
 * @method static Money XOF(mixed $amount, bool $convert = false)
 * @method static Money XPF(mixed $amount, bool $convert = false)
 * @method static Money YER(mixed $amount, bool $convert = false)
 * @method static Money ZAR(mixed $amount, bool $convert = false)
 * @method static Money ZMW(mixed $amount, bool $convert = false)
 * @method static Money ZWL(mixed $amount, bool $convert = false)
 */
class Money implements Arrayable, Castable, Jsonable, JsonSerializable, Renderable
{
    use Macroable {
        __callStatic as protected macroableCallStatic;
    }

    const ROUND_HALF_UP = PHP_ROUND_HALF_UP;

    const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;

    const ROUND_HALF_EVEN = PHP_ROUND_HALF_EVEN;

    const ROUND_HALF_ODD = PHP_ROUND_HALF_ODD;

    protected int|float $amount;

    protected Currency $currency;

    protected bool $mutable = false;

    protected static string $locale;

    /**
     * Create a new instance.
     *
     * @throws UnexpectedAmountException
     */
    public function __construct(mixed $amount, Currency $currency, bool $convert = false)
    {
        $this->currency = $currency;
        $this->amount = $this->parseAmount($amount, $convert);
    }

    /**
     * parseAmount.
     *
     * @throws UnexpectedAmountException
     */
    protected function parseAmount(mixed $amount, bool $convert = false): int|float
    {
        /** @var int|float|Money $amount */
        $amount = $this->parseAmountFromString($this->parseAmountFromCallable($amount));

        if (is_int($amount)) {
            return (int) $this->convertAmount($amount, $convert);
        }

        if (is_float($amount)) {
            return $this->round($this->convertAmount($amount, $convert));
        }

        if ($amount instanceof static) {
            return $this->convertAmount($amount->getAmount(), $convert);
        }

        throw new UnexpectedAmountException('Invalid amount "' . $amount . '"');
    }

    protected function parseAmountFromCallable(mixed $amount): mixed
    {
        if (!is_callable($amount)) {
            return $amount;
        }

        return $amount();
    }

    protected function parseAmountFromString(mixed $amount): mixed
    {
        if (!is_string($amount)) {
            return $amount;
        }

        $thousandsSeparator = $this->currency->getThousandsSeparator();
        $decimalMark = $this->currency->getDecimalMark();

        $amount = str_replace($this->currency->getSymbol(), '', $amount);
        $amount = preg_replace('/[^\d\\' . $thousandsSeparator . '\\' . $decimalMark . '\-\+]/', '', $amount);
        $amount = str_replace($this->currency->getThousandsSeparator(), '', $amount);
        $amount = str_replace($this->currency->getDecimalMark(), '.', $amount);

        if (preg_match('/^([\-\+])?\d+$/', $amount)) {
            $amount = (int) $amount;
        } elseif (preg_match('/^([\-\+])?\d+\.\d+$/', $amount)) {
            $amount = (float) $amount;
        }

        return $amount;
    }

    protected function convertAmount(int|float $amount, bool $convert = false): int|float
    {
        if (!$convert) {
            return $amount;
        }

        return $amount * $this->currency->getSubunit();
    }

    /**
     * @psalm-suppress MixedInferredReturnType,MixedReturnStatement
     */
    public static function __callStatic(string $method, array $arguments): Money
    {
        if (static::hasMacro($method)) {
            return static::macroableCallStatic($method, $arguments);
        }

        $convert = isset($arguments[1]) && is_bool($arguments[1]) && $arguments[1];

        return new self($arguments[0], new Currency($method), $convert);
    }

    /**
     * castUsing
     *
     * @return class-string<CastsAttributes>
     */
    public static function castUsing(array $arguments): string
    {
        return MoneyCast::class;
    }

    public static function getLocale(): string
    {
        if (empty(static::$locale)) {
            static::$locale = 'en_GB';
        }

        return static::$locale;
    }

    public static function setLocale(?string $locale): void
    {
        static::$locale = str_replace('-', '_', (string) $locale);
    }

    /**
     * assertSameCurrency.
     *
     * @throws InvalidArgumentException
     */
    protected function assertSameCurrency(Money $other): void
    {
        if (!$this->isSameCurrency($other)) {
            throw new InvalidArgumentException('Different currencies "' . $this->currency . '" and "' . $other->currency . '"');
        }
    }

    protected function assertRoundingMode(int $mode): void
    {
        $modes = [self::ROUND_HALF_UP, self::ROUND_HALF_DOWN, self::ROUND_HALF_EVEN, self::ROUND_HALF_ODD];

        if (! in_array($mode, $modes)) {
            throw new OutOfBoundsException('Rounding mode should be ' . implode(' | ', $modes));
        }
    }

    /**
     * assertDivisor.
     *
     * @throws InvalidArgumentException
     */
    protected function assertDivisor(int|float $divisor): void
    {
        if ($divisor == 0) {
            throw new InvalidArgumentException('Division by zero');
        }
    }

    public function getAmount(bool $rounded = false): int|float
    {
        return $rounded ? $this->getRoundedAmount() : $this->amount;
    }

    public function getRoundedAmount(): int|float
    {
        return $this->round($this->amount);
    }

    public function getValue(): float
    {
        return $this->round($this->amount / $this->currency->getSubunit());
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function isSameCurrency(Money $other): bool
    {
        return $this->currency->equals($other->currency);
    }

    /**
     * compare.
     *
     * @throws InvalidArgumentException
     */
    public function compare(Money $other): int
    {
        $this->assertSameCurrency($other);

        if ($this->amount < $other->amount) {
            return -1;
        }

        if ($this->amount > $other->amount) {
            return 1;
        }

        return 0;
    }

    public function equals(Money $other): bool
    {
        return $this->compare($other) == 0;
    }

    public function greaterThan(Money $other): bool
    {
        return $this->compare($other) == 1;
    }

    public function greaterThanOrEqual(Money $other): bool
    {
        return $this->compare($other) >= 0;
    }

    public function lessThan(Money $other): bool
    {
        return $this->compare($other) == -1;
    }

    public function lessThanOrEqual(Money $other): bool
    {
        return $this->compare($other) <= 0;
    }

    public function convert(Currency $currency, int|float $ratio, int $roundingMode = self::ROUND_HALF_UP): Money
    {
        $this->currency = $currency;

        return $this->multiply($ratio, $roundingMode);
    }

    public function add(int|float|Money $addend, int $roundingMode = self::ROUND_HALF_UP): Money
    {
        if ($addend instanceof Money) {
            $this->assertSameCurrency($addend);

            $addend = $addend->getAmount();
        }

        $amount = $this->round($this->amount + $addend, $roundingMode);

        if ($this->isImmutable()) {
            return new self($amount, $this->currency);
        }

        $this->amount = $amount;

        return $this;
    }

    public function subtract(int|float|Money $subtrahend, int $roundingMode = self::ROUND_HALF_UP): Money
    {
        if ($subtrahend instanceof Money) {
            $this->assertSameCurrency($subtrahend);

            $subtrahend = $subtrahend->getAmount();
        }

        $amount = $this->round($this->amount - $subtrahend, $roundingMode);

        if ($this->isImmutable()) {
            return new self($amount, $this->currency);
        }

        $this->amount = $amount;

        return $this;
    }

    public function multiply(int|float $multiplier, int $roundingMode = self::ROUND_HALF_UP): Money
    {
        $amount = $this->round($this->amount * $multiplier, $roundingMode);

        if ($this->isImmutable()) {
            return new self($amount, $this->currency);
        }

        $this->amount = $amount;

        return $this;
    }

    public function divide(int|float $divisor, int $roundingMode = self::ROUND_HALF_UP): Money
    {
        $this->assertDivisor($divisor);

        $amount = $this->round($this->amount / $divisor, $roundingMode);

        if ($this->isImmutable()) {
            return new self($amount, $this->currency);
        }

        $this->amount = $amount;

        return $this;
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function round(int|float $amount, int $mode = self::ROUND_HALF_UP): float
    {
        $this->assertRoundingMode($mode);

        return round($amount, $this->currency->getPrecision(), $mode);
    }

    /**
     * @param array<array-key,int|float> $ratios
     */
    public function allocate(array $ratios): array
    {
        $remainder = $this->amount;
        $results = [];
        $total = array_sum($ratios);

        foreach ($ratios as $ratio) {
            $share = floor($this->amount * $ratio / $total);
            $results[] = new self($share, $this->currency);
            $remainder -= $share;
        }

        for ($i = 0; $remainder > 0; $i++) {
            $results[$i]->amount++;
            $remainder--;
        }

        return $results;
    }

    public function isZero(): bool
    {
        return $this->amount == 0;
    }

    public function isPositive(): bool
    {
        return $this->amount > 0;
    }

    public function isNegative(): bool
    {
        return $this->amount < 0;
    }

    public function format(): string
    {
        $negative = $this->isNegative();
        $value = $this->getValue();
        $amount = $negative ? -$value : $value;
        $thousands = $this->currency->getThousandsSeparator();
        $decimals = $this->currency->getDecimalMark();
        $prefix = $this->currency->getPrefix();
        $suffix = $this->currency->getSuffix();
        $value = number_format($amount, $this->currency->getPrecision(), $decimals, $thousands);

        return ($negative ? '-' : '') . $prefix . $value . $suffix;
    }

    public function formatSimple(): string
    {
        return number_format(
            $this->getValue(),
            $this->currency->getPrecision(),
            $this->currency->getDecimalMark(),
            $this->currency->getThousandsSeparator()
        );
    }

    public function formatWithoutZeroes(): string
    {
        if ($this->getValue() !== round($this->getValue())) {
            return $this->format();
        }

        $negative = $this->isNegative();
        $value = $this->getValue();
        $amount = $negative ? -$value : $value;
        $thousands = $this->currency->getThousandsSeparator();
        $decimals = $this->currency->getDecimalMark();
        $prefix = $this->currency->getPrefix();
        $suffix = $this->currency->getSuffix();
        $value = number_format($amount, 0, $decimals, $thousands);

        return ($negative ? '-' : '') . $prefix . $value . $suffix;
    }

    /**
     * formatForHumans.
     *
     * @throws BadFunctionCallException
     */
    public function formatForHumans(?string $locale = null, ?Closure $callback = null): string
    {
        // @codeCoverageIgnoreStart
        if (! class_exists('\NumberFormatter')) {
            throw new BadFunctionCallException('Class NumberFormatter not exists. Require ext-intl extension.');
        }
        // @codeCoverageIgnoreEnd

        $negative = $this->isNegative();
        $value = $this->getValue();
        $amount = $negative ? -$value : $value;
        $prefix = $this->currency->getPrefix();
        $suffix = $this->currency->getSuffix();

        $formatter = new \NumberFormatter($locale ?: static::getLocale(), \NumberFormatter::PADDING_POSITION);

        $formatter->setSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL, $this->currency->getDecimalMark());
        $formatter->setSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, $this->currency->getThousandsSeparator());
        $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $this->currency->getPrecision());

        if (is_callable($callback)) {
            $callback($formatter);
        }

        return ($negative ? '-' : '') . $prefix . $formatter->format($amount) . $suffix;
    }

    /**
     * formatLocale.
     *
     * @throws BadFunctionCallException
     */
    public function formatLocale(?string $locale = null, ?Closure $callback = null): string
    {
        // @codeCoverageIgnoreStart
        if (! class_exists('\NumberFormatter')) {
            throw new BadFunctionCallException('Class NumberFormatter not exists. Require ext-intl extension.');
        }
        // @codeCoverageIgnoreEnd

        $formatter = new \NumberFormatter($locale ?: static::getLocale(), \NumberFormatter::CURRENCY);

        $formatter->setSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL, $this->currency->getDecimalMark());
        $formatter->setSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, $this->currency->getThousandsSeparator());
        $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $this->currency->getPrecision());

        if (is_callable($callback)) {
            $callback($formatter);
        }

        return $formatter->formatCurrency($this->getValue(), $this->currency->getCurrency());
    }

    public function toArray(): array
    {
        return [
            'amount'   => $this->amount,
            'value'    => $this->getValue(),
            'currency' => $this->currency,
        ];
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function render(): string
    {
        return $this->format();
    }

    public function immutable(): Money
    {
        $this->mutable = false;

        return new self($this->amount, $this->currency);
    }

    public function mutable(): Money
    {
        $this->mutable = true;

        return $this;
    }

    public function isMutable(): bool
    {
        return $this->mutable === true;
    }

    public function isImmutable(): bool
    {
        return !$this->isMutable();
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
