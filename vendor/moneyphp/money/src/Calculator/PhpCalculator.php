<?php

namespace Money\Calculator;

use Money\Calculator;
use Money\Money;
use Money\Number;

/**
 * @author Frederik Bosch <f.bosch@genkgo.nl>
 */
final class PhpCalculator implements Calculator
{
    /**
     * {@inheritdoc}
     */
    public static function supported()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function compare($a, $b)
    {
        return ($a < $b) ? -1 : (($a > $b) ? 1 : 0);
    }

    /**
     * {@inheritdoc}
     */
    public function add($amount, $addend)
    {
        $result = $amount + $addend;

        $this->assertInteger($result);

        return (string) $result;
    }

    /**
     * {@inheritdoc}
     */
    public function subtract($amount, $subtrahend)
    {
        $result = $amount - $subtrahend;

        $this->assertInteger($result);

        return (string) $result;
    }

    /**
     * {@inheritdoc}
     */
    public function multiply($amount, $multiplier)
    {
        $result = $amount * $multiplier;

        $this->assertIntegerBounds($result);

        return (string) Number::fromNumber($result);
    }

    /**
     * {@inheritdoc}
     */
    public function divide($amount, $divisor)
    {
        $result = $amount / $divisor;

        $this->assertIntegerBounds($result);

        return (string) Number::fromNumber($result);
    }

    /**
     * {@inheritdoc}
     */
    public function ceil($number)
    {
        return $this->castInteger(ceil($number));
    }

    /**
     * {@inheritdoc}
     */
    public function floor($number)
    {
        return $this->castInteger(floor($number));
    }

    /**
     * {@inheritdoc}
     */
    public function absolute($number)
    {
        $result = ltrim($number, '-');

        $this->assertIntegerBounds($result);

        return (string) $result;
    }

    /**
     * {@inheritdoc}
     */
    public function round($number, $roundingMode)
    {
        if (Money::ROUND_HALF_POSITIVE_INFINITY === $roundingMode) {
            $number = Number::fromNumber($number);

            if ($number->isHalf()) {
                return $this->castInteger(ceil((string) $number));
            }

            return $this->castInteger(round((string) $number, 0, Money::ROUND_HALF_UP));
        }

        if (Money::ROUND_HALF_NEGATIVE_INFINITY === $roundingMode) {
            $number = Number::fromNumber($number);

            if ($number->isHalf()) {
                return $this->castInteger(floor((string) $number));
            }

            return $this->castInteger(round((string) $number, 0, Money::ROUND_HALF_DOWN));
        }

        return $this->castInteger(round($number, 0, $roundingMode));
    }

    /**
     * {@inheritdoc}
     */
    public function share($amount, $ratio, $total)
    {
        return $this->castInteger(floor($amount * $ratio / $total));
    }

    /**
     * {@inheritdoc}
     */
    public function mod($amount, $divisor)
    {
        $result = $amount % $divisor;

        $this->assertIntegerBounds($result);

        return (string) $result;
    }

    /**
     * Asserts that an integer value didn't become something else
     * (after some arithmetic operation).
     *
     * @param int $amount
     *
     * @throws \OverflowException  If integer overflow occured
     * @throws \UnderflowException If integer underflow occured
     */
    private function assertIntegerBounds($amount)
    {
        if ($amount > PHP_INT_MAX) {
            throw new \OverflowException('You overflowed the maximum allowed integer (PHP_INT_MAX)');
        } elseif ($amount < ~PHP_INT_MAX) {
            throw new \UnderflowException('You underflowed the minimum allowed integer (PHP_INT_MAX)');
        }
    }

    /**
     * Casts an amount to integer ensuring that an overflow/underflow did not occur.
     *
     * @param int $amount
     *
     * @return string
     */
    private function castInteger($amount)
    {
        $this->assertIntegerBounds($amount);

        return (string) intval($amount);
    }

    /**
     * Asserts that integer remains integer after arithmetic operations.
     *
     * @param int $amount
     */
    private function assertInteger($amount)
    {
        if (filter_var($amount, FILTER_VALIDATE_INT) === false) {
            throw new \UnexpectedValueException('The result of arithmetic operation is not an integer');
        }
    }
}
