<?php

declare(strict_types=1);

namespace Money;

use InvalidArgumentException;
use JsonSerializable;
use Money\Calculator\BcMathCalculator;

use function array_fill;
use function array_keys;
use function array_map;
use function array_sum;
use function count;
use function filter_var;
use function floor;
use function is_int;
use function max;
use function str_pad;
use function strlen;
use function substr;

use const FILTER_VALIDATE_INT;
use const PHP_ROUND_HALF_DOWN;
use const PHP_ROUND_HALF_EVEN;
use const PHP_ROUND_HALF_ODD;
use const PHP_ROUND_HALF_UP;

/**
 * Money Value Object.
 *
 * @psalm-immutable
 */
final class Money implements JsonSerializable
{
    use MoneyFactory;

    public const ROUND_HALF_UP = PHP_ROUND_HALF_UP;

    public const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;

    public const ROUND_HALF_EVEN = PHP_ROUND_HALF_EVEN;

    public const ROUND_HALF_ODD = PHP_ROUND_HALF_ODD;

    public const ROUND_UP = 5;

    public const ROUND_DOWN = 6;

    public const ROUND_HALF_POSITIVE_INFINITY = 7;

    public const ROUND_HALF_NEGATIVE_INFINITY = 8;

    /**
     * Internal value.
     *
     * @psalm-var numeric-string
     */
    private string $amount;

    private Currency $currency;

    /**
     * @var Calculator
     * @psalm-var class-string<Calculator>
     */
    private static string $calculator = BcMathCalculator::class;

    /**
     * @param int|string $amount Amount, expressed in the smallest units of $currency (eg cents)
     * @psalm-param int|numeric-string $amount
     *
     * @throws InvalidArgumentException If amount is not integer(ish).
     */
    public function __construct(int|string $amount, Currency $currency)
    {
        $this->currency = $currency;

        if (filter_var($amount, FILTER_VALIDATE_INT) === false) {
            $numberFromString = Number::fromString((string) $amount);
            if (! $numberFromString->isInteger()) {
                throw new InvalidArgumentException('Amount must be an integer(ish) value');
            }

            $this->amount = $numberFromString->getIntegerPart();

            return;
        }

        $this->amount = (string) $amount;
    }

    /**
     * Checks whether a Money has the same Currency as this.
     */
    public function isSameCurrency(Money ...$others): bool
    {
        foreach ($others as $other) {
            // Note: non-strict equality is intentional here, since `Currency` is `final` and reliable.
            if ($this->currency != $other->currency) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks whether the value represented by this object equals to the other.
     */
    public function equals(Money $other): bool
    {
        // Note: non-strict equality is intentional here, since `Currency` is `final` and reliable.
        if ($this->currency != $other->currency) {
            return false;
        }

        if ($this->amount === $other->amount) {
            return true;
        }

        // @TODO do we want Money instance to be byte-equivalent when trailing zeroes exist? Very expensive!
        // Assumption: Money#equals() is called **less** than other number-based comparisons, and probably
        // only within test suites. Therefore, using complex normalization here is acceptable waste of performance.
        return $this->compare($other) === 0;
    }

    /**
     * Returns an integer less than, equal to, or greater than zero
     * if the value of this object is considered to be respectively
     * less than, equal to, or greater than the other.
     */
    public function compare(Money $other): int
    {
        // Note: non-strict equality is intentional here, since `Currency` is `final` and reliable.
        if ($this->currency != $other->currency) {
            throw new InvalidArgumentException('Currencies must be identical');
        }

        return self::$calculator::compare($this->amount, $other->amount);
    }

    /**
     * Checks whether the value represented by this object is greater than the other.
     */
    public function greaterThan(Money $other): bool
    {
        return $this->compare($other) > 0;
    }

    public function greaterThanOrEqual(Money $other): bool
    {
        return $this->compare($other) >= 0;
    }

    /**
     * Checks whether the value represented by this object is less than the other.
     */
    public function lessThan(Money $other): bool
    {
        return $this->compare($other) < 0;
    }

    public function lessThanOrEqual(Money $other): bool
    {
        return $this->compare($other) <= 0;
    }

    /**
     * Returns the value represented by this object.
     *
     * @psalm-return numeric-string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * Returns the currency of this object.
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * Returns a new Money object that represents
     * the sum of this and an other Money object.
     *
     * @param Money[] $addends
     */
    public function add(Money ...$addends): Money
    {
        $amount = $this->amount;

        foreach ($addends as $addend) {
            // Note: non-strict equality is intentional here, since `Currency` is `final` and reliable.
            if ($this->currency != $addend->currency) {
                throw new InvalidArgumentException('Currencies must be identical');
            }

            $amount = self::$calculator::add($amount, $addend->amount);
        }

        return new self($amount, $this->currency);
    }

    /**
     * Returns a new Money object that represents
     * the difference of this and an other Money object.
     *
     * @param Money[] $subtrahends
     *
     * @psalm-pure
     */
    public function subtract(Money ...$subtrahends): Money
    {
        $amount = $this->amount;

        foreach ($subtrahends as $subtrahend) {
            // Note: non-strict equality is intentional here, since `Currency` is `final` and reliable.
            if ($this->currency != $subtrahend->currency) {
                throw new InvalidArgumentException('Currencies must be identical');
            }

            $amount = self::$calculator::subtract($amount, $subtrahend->amount);
        }

        return new self($amount, $this->currency);
    }

    /**
     * Returns a new Money object that represents
     * the multiplied value by the given factor.
     *
     * @psalm-param int|numeric-string $multiplier
     * @psalm-param self::ROUND_*  $roundingMode
     */
    public function multiply(int|string $multiplier, int $roundingMode = self::ROUND_HALF_UP): Money
    {
        if (is_int($multiplier)) {
            $multiplier = (string) $multiplier;
        }

        $product = $this->round(self::$calculator::multiply($this->amount, $multiplier), $roundingMode);

        return new self($product, $this->currency);
    }

    /**
     * Returns a new Money object that represents
     * the divided value by the given factor.
     *
     * @psalm-param int|numeric-string $divisor
     * @psalm-param self::ROUND_*  $roundingMode
     */
    public function divide(int|string $divisor, int $roundingMode = self::ROUND_HALF_UP): Money
    {
        if (is_int($divisor)) {
            $divisor = (string) $divisor;
        }

        $quotient = $this->round(self::$calculator::divide($this->amount, $divisor), $roundingMode);

        return new self($quotient, $this->currency);
    }

    /**
     * Returns a new Money object that represents
     * the remainder after dividing the value by
     * the given factor.
     */
    public function mod(Money $divisor): Money
    {
        // Note: non-strict equality is intentional here, since `Currency` is `final` and reliable.
        if ($this->currency != $divisor->currency) {
            throw new InvalidArgumentException('Currencies must be identical');
        }

        return new self(self::$calculator::mod($this->amount, $divisor->amount), $this->currency);
    }

    /**
     * Allocate the money according to a list of ratios.
     *
     * @psalm-param TRatios $ratios
     *
     * @return Money[]
     * @psalm-return (
     *     TRatios is list
     *         ? non-empty-list<Money>
     *         : non-empty-array<Money>
     * )
     *
     * @template TRatios as non-empty-array<float|int>
     */
    public function allocate(array $ratios): array
    {
        $remainder = $this->amount;
        $results   = [];
        $total     = array_sum($ratios);

        if ($total <= 0) {
            throw new InvalidArgumentException('Cannot allocate to none, sum of ratios must be greater than zero');
        }

        foreach ($ratios as $key => $ratio) {
            if ($ratio < 0) {
                throw new InvalidArgumentException('Cannot allocate to none, ratio must be zero or positive');
            }

            $share         = self::$calculator::share($this->amount, (string) $ratio, (string) $total);
            $results[$key] = new self($share, $this->currency);
            $remainder     = self::$calculator::subtract($remainder, $share);
        }

        if (self::$calculator::compare($remainder, '0') === 0) {
            return $results;
        }

        $amount    = $this->amount;
        $fractions = array_map(static function (float|int $ratio) use ($total, $amount) {
            $share = (float) $ratio / $total * (float) $amount;

            return $share - floor($share);
        }, $ratios);

        while (self::$calculator::compare($remainder, '0') > 0) {
            $index           = $fractions !== [] ? array_keys($fractions, max($fractions))[0] : 0;
            $results[$index] = new self(self::$calculator::add($results[$index]->amount, '1'), $results[$index]->currency);
            $remainder       = self::$calculator::subtract($remainder, '1');
            unset($fractions[$index]);
        }

        return $results;
    }

    /**
     * Allocate the money among N targets.
     *
     * @psalm-param positive-int $n
     *
     * @return Money[]
     * @psalm-return non-empty-list<Money>
     *
     * @throws InvalidArgumentException If number of targets is not an integer.
     */
    public function allocateTo(int $n): array
    {
        return $this->allocate(array_fill(0, $n, 1));
    }

    /**
     * @psalm-return numeric-string
     *
     * @throws InvalidArgumentException if the given $money is zero.
     */
    public function ratioOf(Money $money): string
    {
        if ($money->isZero()) {
            throw new InvalidArgumentException('Cannot calculate a ratio of zero');
        }

        // Note: non-strict equality is intentional here, since `Currency` is `final` and reliable.
        if ($this->currency != $money->currency) {
            throw new InvalidArgumentException('Currencies must be identical');
        }

        return self::$calculator::divide($this->amount, $money->amount);
    }

    /**
     * @psalm-param numeric-string $amount
     * @psalm-param self::ROUND_*  $roundingMode
     *
     * @psalm-return numeric-string
     */
    private function round(string $amount, int $roundingMode): string
    {
        if ($roundingMode === self::ROUND_UP) {
            return self::$calculator::ceil($amount);
        }

        if ($roundingMode === self::ROUND_DOWN) {
            return self::$calculator::floor($amount);
        }

        return self::$calculator::round($amount, $roundingMode);
    }

    /**
     * Round to a specific unit.
     *
     * @psalm-param positive-int|0  $unit
     * @psalm-param self::ROUND_* $roundingMode
     */
    public function roundToUnit(int $unit, int $roundingMode = self::ROUND_HALF_UP): self
    {
        if ($unit === 0) {
            return $this;
        }

        $abs = self::$calculator::absolute($this->amount);
        if (strlen($abs) < $unit) {
            return new self('0', $this->currency);
        }

        /** @psalm-var numeric-string $toBeRounded */
        $toBeRounded = substr($this->amount, 0, strlen($this->amount) - $unit) . '.' . substr($this->amount, $unit * -1);

        $result = $this->round($toBeRounded, $roundingMode);
        if ($result !== '0') {
            $result .= str_pad('', $unit, '0');
        }

        /** @psalm-var numeric-string $result */
        return new self($result, $this->currency);
    }

    public function absolute(): Money
    {
        return new self(
            self::$calculator::absolute($this->amount),
            $this->currency
        );
    }

    public function negative(): Money
    {
        return (new self(0, $this->currency))
            ->subtract($this);
    }

    /**
     * Checks if the value represented by this object is zero.
     */
    public function isZero(): bool
    {
        return self::$calculator::compare($this->amount, '0') === 0;
    }

    /**
     * Checks if the value represented by this object is positive.
     */
    public function isPositive(): bool
    {
        return self::$calculator::compare($this->amount, '0') > 0;
    }

    /**
     * Checks if the value represented by this object is negative.
     */
    public function isNegative(): bool
    {
        return self::$calculator::compare($this->amount, '0') < 0;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-return array{amount: string, currency: string}
     */
    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency->jsonSerialize(),
        ];
    }

    /**
     * @param Money $first
     * @param Money ...$collection
     *
     * @psalm-pure
     */
    public static function min(self $first, self ...$collection): Money
    {
        $min = $first;

        foreach ($collection as $money) {
            if (! $money->lessThan($min)) {
                continue;
            }

            $min = $money;
        }

        return $min;
    }

    /**
     * @param Money $first
     * @param Money ...$collection
     *
     * @psalm-pure
     */
    public static function max(self $first, self ...$collection): Money
    {
        $max = $first;

        foreach ($collection as $money) {
            if (! $money->greaterThan($max)) {
                continue;
            }

            $max = $money;
        }

        return $max;
    }

    /** @psalm-pure */
    public static function sum(self $first, self ...$collection): Money
    {
        return $first->add(...$collection);
    }

    /** @psalm-pure */
    public static function avg(self $first, self ...$collection): Money
    {
        return $first->add(...$collection)->divide((string) (count($collection) + 1));
    }

    /** @psalm-param class-string<Calculator> $calculator */
    public static function registerCalculator(string $calculator): void
    {
        self::$calculator = $calculator;
    }

    /** @psalm-return class-string<Calculator> */
    public static function getCalculator(): string
    {
        return self::$calculator;
    }
}
