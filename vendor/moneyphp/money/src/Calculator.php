<?php

declare(strict_types=1);

namespace Money;

use Money\Exception\InvalidArgumentException;

/**
 * Money calculations abstracted away from the Money value object.
 *
 * @internal the calculator component is an internal detail of this library: it is only supposed to be replaced if
 *           your system requires a custom architecture for operating on large numbers.
 */
interface Calculator
{
    /**
     * Compare a to b.
     *
     * Retrieves a negative value if $a < $b.
     * Retrieves a positive value if $a > $b.
     * Retrieves zero if $a == $b
     *
     * @psalm-param numeric-string $a
     * @psalm-param numeric-string $b
     *
     * @psalm-pure
     */
    public static function compare(string $a, string $b): int;

    /**
     * Add added to amount.
     *
     * @psalm-param numeric-string $amount
     * @psalm-param numeric-string $addend
     *
     * @psalm-return numeric-string
     *
     * @psalm-pure
     */
    public static function add(string $amount, string $addend): string;

    /**
     * Subtract subtrahend from amount.
     *
     * @psalm-param numeric-string $amount
     * @psalm-param numeric-string $subtrahend
     *
     * @psalm-return numeric-string
     *
     * @psalm-pure
     */
    public static function subtract(string $amount, string $subtrahend): string;

    /**
     * Multiply amount with multiplier.
     *
     * @psalm-param numeric-string $amount
     * @psalm-param numeric-string $multiplier
     *
     * @psalm-return numeric-string
     *
     * @psalm-pure
     */
    public static function multiply(string $amount, string $multiplier): string;

    /**
     * Divide amount with divisor.
     *
     * @psalm-param numeric-string $amount
     * @psalm-param numeric-string $divisor
     *
     * @psalm-return numeric-string
     *
     * @throws InvalidArgumentException when $divisor is zero.
     *
     * @psalm-pure
     */
    public static function divide(string $amount, string $divisor): string;

    /**
     * Round number to following integer.
     *
     * @psalm-param numeric-string $number
     *
     * @psalm-return numeric-string
     *
     * @psalm-pure
     */
    public static function ceil(string $number): string;

    /**
     * Round number to preceding integer.
     *
     * @psalm-param numeric-string $number
     *
     * @psalm-return numeric-string
     *
     * @psalm-pure
     */
    public static function floor(string $number): string;

    /**
     * Returns the absolute value of the number.
     *
     * @psalm-param numeric-string $number
     *
     * @psalm-return numeric-string
     *
     * @psalm-pure
     */
    public static function absolute(string $number): string;

    /**
     * Round number, use rounding mode for tie-breaker.
     *
     * @psalm-param numeric-string $number
     * @psalm-param Money::ROUND_* $roundingMode
     *
     * @psalm-return numeric-string
     *
     * @psalm-pure
     */
    public static function round(string $number, int $roundingMode): string;

    /**
     * Share amount among ratio / total portions.
     *
     * @psalm-param numeric-string $amount
     * @psalm-param numeric-string $ratio
     * @psalm-param numeric-string $total
     *
     * @psalm-return numeric-string
     *
     * @psalm-pure
     */
    public static function share(string $amount, string $ratio, string $total): string;

    /**
     * Get the modulus of an amount.
     *
     * @psalm-param numeric-string $amount
     * @psalm-param numeric-string $divisor
     *
     * @psalm-return numeric-string
     *
     * @throws InvalidArgumentException when $divisor is zero.
     *
     * @psalm-pure
     */
    public static function mod(string $amount, string $divisor): string;
}
