<?php

namespace Money;

use Money\Calculator\BcMathCalculator;
use Money\Calculator\GmpCalculator;
use Money\Calculator\PhpCalculator;

/**
 * Money Value Object.
 *
 * @author Mathias Verraes
 *
 * @psalm-immutable
 */
final class Money implements \JsonSerializable
{
    use MoneyFactory;

    const ROUND_HALF_UP = PHP_ROUND_HALF_UP;

    const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;

    const ROUND_HALF_EVEN = PHP_ROUND_HALF_EVEN;

    const ROUND_HALF_ODD = PHP_ROUND_HALF_ODD;

    const ROUND_UP = 5;

    const ROUND_DOWN = 6;

    const ROUND_HALF_POSITIVE_INFINITY = 7;

    const ROUND_HALF_NEGATIVE_INFINITY = 8;

    /**
     * Internal value.
     *
     * @var string
     */
    private $amount;

    /**
     * @var Currency
     */
    private $currency;

    /**
     * @var Calculator
     */
    private static $calculator;

    /**
     * @var array
     */
    private static $calculators = [
        BcMathCalculator::class,
        GmpCalculator::class,
        PhpCalculator::class,
    ];

    /**
     * @param int|string $amount   Amount, expressed in the smallest units of $currency (eg cents)
     * @param Currency   $currency
     *
     * @throws \InvalidArgumentException If amount is not integer
     */
    public function __construct($amount, Currency $currency)
    {
        if (filter_var($amount, FILTER_VALIDATE_INT) === false) {
            $numberFromString = Number::fromString($amount);
            if (!$numberFromString->isInteger()) {
                throw new \InvalidArgumentException('Amount must be an integer(ish) value');
            }

            $amount = $numberFromString->getIntegerPart();
        }

        $this->amount = (string) $amount;
        $this->currency = $currency;
    }

    /**
     * Returns a new Money instance based on the current one using the Currency.
     *
     * @param int|string $amount
     *
     * @return Money
     *
     * @throws \InvalidArgumentException If amount is not integer
     */
    private function newInstance($amount)
    {
        return new self($amount, $this->currency);
    }

    /**
     * Checks whether a Money has the same Currency as this.
     *
     * @param Money $other
     *
     * @return bool
     */
    public function isSameCurrency(Money $other)
    {
        return $this->currency->equals($other->currency);
    }

    /**
     * Asserts that a Money has the same currency as this.
     *
     * @param Money $other
     *
     * @throws \InvalidArgumentException If $other has a different currency
     */
    private function assertSameCurrency(Money $other)
    {
        if (!$this->isSameCurrency($other)) {
            throw new \InvalidArgumentException('Currencies must be identical');
        }
    }

    /**
     * Checks whether the value represented by this object equals to the other.
     *
     * @param Money $other
     *
     * @return bool
     */
    public function equals(Money $other)
    {
        return $this->isSameCurrency($other) && $this->amount === $other->amount;
    }

    /**
     * Returns an integer less than, equal to, or greater than zero
     * if the value of this object is considered to be respectively
     * less than, equal to, or greater than the other.
     *
     * @param Money $other
     *
     * @return int
     */
    public function compare(Money $other)
    {
        $this->assertSameCurrency($other);

        return $this->getCalculator()->compare($this->amount, $other->amount);
    }

    /**
     * Checks whether the value represented by this object is greater than the other.
     *
     * @param Money $other
     *
     * @return bool
     */
    public function greaterThan(Money $other)
    {
        return $this->compare($other) > 0;
    }

    /**
     * @param \Money\Money $other
     *
     * @return bool
     */
    public function greaterThanOrEqual(Money $other)
    {
        return $this->compare($other) >= 0;
    }

    /**
     * Checks whether the value represented by this object is less than the other.
     *
     * @param Money $other
     *
     * @return bool
     */
    public function lessThan(Money $other)
    {
        return $this->compare($other) < 0;
    }

    /**
     * @param \Money\Money $other
     *
     * @return bool
     */
    public function lessThanOrEqual(Money $other)
    {
        return $this->compare($other) <= 0;
    }

    /**
     * Returns the value represented by this object.
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Returns the currency of this object.
     *
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Returns a new Money object that represents
     * the sum of this and an other Money object.
     *
     * @param Money[] $addends
     *
     * @return Money
     */
    public function add(Money ...$addends)
    {
        $amount = $this->amount;
        $calculator = $this->getCalculator();

        foreach ($addends as $addend) {
            $this->assertSameCurrency($addend);

            $amount = $calculator->add($amount, $addend->amount);
        }

        return new self($amount, $this->currency);
    }

    /**
     * Returns a new Money object that represents
     * the difference of this and an other Money object.
     *
     * @param Money[] $subtrahends
     *
     * @return Money
     *
     * @psalm-pure
     */
    public function subtract(Money ...$subtrahends)
    {
        $amount = $this->amount;
        $calculator = $this->getCalculator();

        foreach ($subtrahends as $subtrahend) {
            $this->assertSameCurrency($subtrahend);

            $amount = $calculator->subtract($amount, $subtrahend->amount);
        }

        return new self($amount, $this->currency);
    }

    /**
     * Asserts that the operand is integer or float.
     *
     * @param float|int|string $operand
     *
     * @throws \InvalidArgumentException If $operand is neither integer nor float
     */
    private function assertOperand($operand)
    {
        if (!is_numeric($operand)) {
            throw new \InvalidArgumentException(sprintf(
                'Operand should be a numeric value, "%s" given.',
                is_object($operand) ? get_class($operand) : gettype($operand)
            ));
        }
    }

    /**
     * Asserts that rounding mode is a valid integer value.
     *
     * @param int $roundingMode
     *
     * @throws \InvalidArgumentException If $roundingMode is not valid
     */
    private function assertRoundingMode($roundingMode)
    {
        if (!in_array(
            $roundingMode, [
                self::ROUND_HALF_DOWN, self::ROUND_HALF_EVEN, self::ROUND_HALF_ODD,
                self::ROUND_HALF_UP, self::ROUND_UP, self::ROUND_DOWN,
                self::ROUND_HALF_POSITIVE_INFINITY, self::ROUND_HALF_NEGATIVE_INFINITY,
            ], true
        )) {
            throw new \InvalidArgumentException(
                'Rounding mode should be Money::ROUND_HALF_DOWN | '.
                'Money::ROUND_HALF_EVEN | Money::ROUND_HALF_ODD | '.
                'Money::ROUND_HALF_UP | Money::ROUND_UP | Money::ROUND_DOWN'.
                'Money::ROUND_HALF_POSITIVE_INFINITY | Money::ROUND_HALF_NEGATIVE_INFINITY'
            );
        }
    }

    /**
     * Returns a new Money object that represents
     * the multiplied value by the given factor.
     *
     * @param float|int|string $multiplier
     * @param int              $roundingMode
     *
     * @return Money
     */
    public function multiply($multiplier, $roundingMode = self::ROUND_HALF_UP)
    {
        $this->assertOperand($multiplier);
        $this->assertRoundingMode($roundingMode);

        $product = $this->round($this->getCalculator()->multiply($this->amount, $multiplier), $roundingMode);

        return $this->newInstance($product);
    }

    /**
     * Returns a new Money object that represents
     * the divided value by the given factor.
     *
     * @param float|int|string $divisor
     * @param int              $roundingMode
     *
     * @return Money
     */
    public function divide($divisor, $roundingMode = self::ROUND_HALF_UP)
    {
        $this->assertOperand($divisor);
        $this->assertRoundingMode($roundingMode);

        $divisor = (string) Number::fromNumber($divisor);

        if ($this->getCalculator()->compare($divisor, '0') === 0) {
            throw new \InvalidArgumentException('Division by zero');
        }

        $quotient = $this->round($this->getCalculator()->divide($this->amount, $divisor), $roundingMode);

        return $this->newInstance($quotient);
    }

    /**
     * Returns a new Money object that represents
     * the remainder after dividing the value by
     * the given factor.
     *
     * @param Money $divisor
     *
     * @return Money
     */
    public function mod(Money $divisor)
    {
        $this->assertSameCurrency($divisor);

        return new self($this->getCalculator()->mod($this->amount, $divisor->amount), $this->currency);
    }

    /**
     * Allocate the money according to a list of ratios.
     *
     * @param array $ratios
     *
     * @return Money[]
     */
    public function allocate(array $ratios)
    {
        if (count($ratios) === 0) {
            throw new \InvalidArgumentException('Cannot allocate to none, ratios cannot be an empty array');
        }

        $remainder = $this->amount;
        $results = [];
        $total = array_sum($ratios);

        if ($total <= 0) {
            throw new \InvalidArgumentException('Cannot allocate to none, sum of ratios must be greater than zero');
        }

        foreach ($ratios as $key => $ratio) {
            if ($ratio < 0) {
                throw new \InvalidArgumentException('Cannot allocate to none, ratio must be zero or positive');
            }
            $share = $this->getCalculator()->share($this->amount, $ratio, $total);
            $results[$key] = $this->newInstance($share);
            $remainder = $this->getCalculator()->subtract($remainder, $share);
        }

        if ($this->getCalculator()->compare($remainder, '0') === 0) {
            return $results;
        }

        $fractions = array_map(function ($ratio) use ($total) {
            $share = ($ratio / $total) * $this->amount;

            return $share - floor($share);
        }, $ratios);

        while ($this->getCalculator()->compare($remainder, '0') > 0) {
            $index = !empty($fractions) ? array_keys($fractions, max($fractions))[0] : 0;
            $results[$index]->amount = $this->getCalculator()->add($results[$index]->amount, '1');
            $remainder = $this->getCalculator()->subtract($remainder, '1');
            unset($fractions[$index]);
        }

        return $results;
    }

    /**
     * Allocate the money among N targets.
     *
     * @param int $n
     *
     * @return Money[]
     *
     * @throws \InvalidArgumentException If number of targets is not an integer
     */
    public function allocateTo($n)
    {
        if (!is_int($n)) {
            throw new \InvalidArgumentException('Number of targets must be an integer');
        }

        if ($n <= 0) {
            throw new \InvalidArgumentException('Cannot allocate to none, target must be greater than zero');
        }

        return $this->allocate(array_fill(0, $n, 1));
    }

    /**
     * @param Money $money
     *
     * @return string
     */
    public function ratioOf(Money $money)
    {
        if ($money->isZero()) {
            throw new \InvalidArgumentException('Cannot calculate a ratio of zero');
        }

        return $this->getCalculator()->divide($this->amount, $money->amount);
    }

    /**
     * @param string $amount
     * @param int    $rounding_mode
     *
     * @return string
     */
    private function round($amount, $rounding_mode)
    {
        $this->assertRoundingMode($rounding_mode);

        if ($rounding_mode === self::ROUND_UP) {
            return $this->getCalculator()->ceil($amount);
        }

        if ($rounding_mode === self::ROUND_DOWN) {
            return $this->getCalculator()->floor($amount);
        }

        return $this->getCalculator()->round($amount, $rounding_mode);
    }

    /**
     * @return Money
     */
    public function absolute()
    {
        return $this->newInstance($this->getCalculator()->absolute($this->amount));
    }

    /**
     * @return Money
     */
    public function negative()
    {
        return $this->newInstance(0)->subtract($this);
    }

    /**
     * Checks if the value represented by this object is zero.
     *
     * @return bool
     */
    public function isZero()
    {
        return $this->getCalculator()->compare($this->amount, 0) === 0;
    }

    /**
     * Checks if the value represented by this object is positive.
     *
     * @return bool
     */
    public function isPositive()
    {
        return $this->getCalculator()->compare($this->amount, 0) > 0;
    }

    /**
     * Checks if the value represented by this object is negative.
     *
     * @return bool
     */
    public function isNegative()
    {
        return $this->getCalculator()->compare($this->amount, 0) < 0;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function jsonSerialize()
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
     * @return Money
     *
     * @psalm-pure
     */
    public static function min(self $first, self ...$collection)
    {
        $min = $first;

        foreach ($collection as $money) {
            if ($money->lessThan($min)) {
                $min = $money;
            }
        }

        return $min;
    }

    /**
     * @param Money $first
     * @param Money ...$collection
     *
     * @return Money
     *
     * @psalm-pure
     */
    public static function max(self $first, self ...$collection)
    {
        $max = $first;

        foreach ($collection as $money) {
            if ($money->greaterThan($max)) {
                $max = $money;
            }
        }

        return $max;
    }

    /**
     * @param Money $first
     * @param Money ...$collection
     *
     * @return Money
     *
     * @psalm-pure
     */
    public static function sum(self $first, self ...$collection)
    {
        return $first->add(...$collection);
    }

    /**
     * @param Money $first
     * @param Money ...$collection
     *
     * @return Money
     *
     * @psalm-pure
     */
    public static function avg(self $first, self ...$collection)
    {
        return $first->add(...$collection)->divide(func_num_args());
    }

    /**
     * @param string $calculator
     */
    public static function registerCalculator($calculator)
    {
        if (is_a($calculator, Calculator::class, true) === false) {
            throw new \InvalidArgumentException('Calculator must implement '.Calculator::class);
        }

        array_unshift(self::$calculators, $calculator);
    }

    /**
     * @return Calculator
     *
     * @throws \RuntimeException If cannot find calculator for money calculations
     */
    private static function initializeCalculator()
    {
        $calculators = self::$calculators;

        foreach ($calculators as $calculator) {
            /** @var Calculator $calculator */
            if ($calculator::supported()) {
                return new $calculator();
            }
        }

        throw new \RuntimeException('Cannot find calculator for money calculations');
    }

    /**
     * @return Calculator
     */
    private function getCalculator()
    {
        if (null === self::$calculator) {
            self::$calculator = self::initializeCalculator();
        }

        return self::$calculator;
    }
}
