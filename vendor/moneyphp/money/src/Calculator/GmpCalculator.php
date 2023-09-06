<?php

declare(strict_types=1);

namespace Money\Calculator;

use InvalidArgumentException as CoreInvalidArgumentException;
use Money\Calculator;
use Money\Exception\InvalidArgumentException;
use Money\Money;
use Money\Number;

use function gmp_add;
use function gmp_cmp;
use function gmp_div_q;
use function gmp_div_qr;
use function gmp_init;
use function gmp_mod;
use function gmp_mul;
use function gmp_neg;
use function gmp_strval;
use function gmp_sub;
use function ltrim;
use function str_pad;
use function str_replace;
use function strlen;
use function substr;

use const GMP_ROUND_MINUSINF;
use const STR_PAD_LEFT;

/**
 * @psalm-immutable
 *
 * Important: the {@see GmpCalculator} is not optimized for decimal operations, as GMP
 *            is designed to operate on large integers. Consider using this only if your
 *            system does not have `ext-bcmath` installed.
 */
final class GmpCalculator implements Calculator
{
    private const SCALE = 14;

    /** @psalm-pure */
    public static function compare(string $a, string $b): int
    {
        $aNum = Number::fromString($a);
        $bNum = Number::fromString($b);

        if ($aNum->isDecimal() || $bNum->isDecimal()) {
            $integersCompared = gmp_cmp($aNum->getIntegerPart(), $bNum->getIntegerPart());
            if ($integersCompared !== 0) {
                return $integersCompared;
            }

            $aNumFractional = $aNum->getFractionalPart() === '' ? '0' : $aNum->getFractionalPart();
            $bNumFractional = $bNum->getFractionalPart() === '' ? '0' : $bNum->getFractionalPart();

            return gmp_cmp($aNumFractional, $bNumFractional);
        }

        return gmp_cmp($a, $b);
    }

    /** @psalm-pure */
    public static function add(string $amount, string $addend): string
    {
        return gmp_strval(gmp_add($amount, $addend));
    }

    /** @psalm-pure */
    public static function subtract(string $amount, string $subtrahend): string
    {
        return gmp_strval(gmp_sub($amount, $subtrahend));
    }

    /** @psalm-pure */
    public static function multiply(string $amount, string $multiplier): string
    {
        $multiplier = Number::fromString($multiplier);

        if ($multiplier->isDecimal()) {
            $decimalPlaces  = strlen($multiplier->getFractionalPart());
            $multiplierBase = $multiplier->getIntegerPart();
            $negativeZero   = $multiplierBase === '-0';

            if ($negativeZero) {
                $multiplierBase = '-';
            }

            if ($multiplierBase) {
                $multiplierBase .= $multiplier->getFractionalPart();
            } else {
                $multiplierBase = ltrim($multiplier->getFractionalPart(), '0');
            }

            $resultBase = gmp_strval(gmp_mul(gmp_init($amount), gmp_init($multiplierBase)));

            if ($resultBase === '0') {
                return '0';
            }

            $result       = substr($resultBase, $decimalPlaces * -1);
            $resultLength = strlen($result);
            if ($decimalPlaces > $resultLength) {
                /** @psalm-var numeric-string $finalResult */
                $finalResult = '0.' . str_pad('', $decimalPlaces - $resultLength, '0') . $result;

                return $finalResult;
            }

            /** @psalm-var numeric-string $finalResult */
            $finalResult = substr($resultBase, 0, $decimalPlaces * -1) . '.' . $result;

            if ($negativeZero) {
                /** @psalm-var numeric-string $finalResult */
                $finalResult = str_replace('-.', '-0.', $finalResult);
            }

            return $finalResult;
        }

        return gmp_strval(gmp_mul(gmp_init($amount), gmp_init((string) $multiplier)));
    }

    /** @psalm-pure */
    public static function divide(string $amount, string $divisor): string
    {
        if (self::compare($divisor, '0') === 0) {
            throw InvalidArgumentException::divisionByZero();
        }

        $divisor = Number::fromString($divisor);

        if ($divisor->isDecimal()) {
            $decimalPlaces = strlen($divisor->getFractionalPart());
            $divisorBase   = $divisor->getIntegerPart();
            $negativeZero  = $divisorBase === '-0';

            if ($negativeZero) {
                $divisorBase = '-';
            }

            if ($divisor->getIntegerPart()) {
                $divisor = new Number($divisorBase . $divisor->getFractionalPart());
            } else {
                $divisor = new Number(ltrim($divisor->getFractionalPart(), '0'));
            }

            $amount = gmp_strval(gmp_mul(gmp_init($amount), gmp_init('1' . str_pad('', $decimalPlaces, '0'))));
        }

        [$integer, $remainder] = gmp_div_qr(gmp_init($amount), gmp_init((string) $divisor));

        if (gmp_cmp($remainder, '0') === 0) {
            return gmp_strval($integer);
        }

        $divisionOfRemainder = gmp_strval(
            gmp_div_q(
                gmp_mul($remainder, gmp_init('1' . str_pad('', self::SCALE, '0'))),
                gmp_init((string) $divisor),
                GMP_ROUND_MINUSINF
            )
        );

        if ($divisionOfRemainder[0] === '-') {
            $divisionOfRemainder = substr($divisionOfRemainder, 1);
        }

        /** @psalm-var numeric-string $finalResult */
        $finalResult = gmp_strval($integer) . '.' . str_pad($divisionOfRemainder, self::SCALE, '0', STR_PAD_LEFT);

        return $finalResult;
    }

    /** @psalm-pure */
    public static function ceil(string $number): string
    {
        $number = Number::fromString($number);

        if ($number->isInteger()) {
            return $number->__toString();
        }

        if ($number->isNegative()) {
            return self::add($number->getIntegerPart(), '0');
        }

        return self::add($number->getIntegerPart(), '1');
    }

    /** @psalm-pure */
    public static function floor(string $number): string
    {
        $number = Number::fromString($number);

        if ($number->isInteger()) {
            return $number->__toString();
        }

        if ($number->isNegative()) {
            return self::add($number->getIntegerPart(), '-1');
        }

        return self::add($number->getIntegerPart(), '0');
    }

    /**
     * @psalm-suppress MoreSpecificReturnType we know that trimming `-` produces a positive numeric-string here
     * @psalm-suppress LessSpecificReturnStatement we know that trimming `-` produces a positive numeric-string here
     * @psalm-pure
     */
    public static function absolute(string $number): string
    {
        return ltrim($number, '-');
    }

    /**
     * @psalm-param Money::ROUND_* $roundingMode
     *
     * @psalm-return numeric-string
     *
     * @psalm-pure
     */
    public static function round(string $number, int $roundingMode): string
    {
        $number = Number::fromString($number);

        if ($number->isInteger()) {
            return $number->__toString();
        }

        if ($number->isHalf() === false) {
            return self::roundDigit($number);
        }

        if ($roundingMode === Money::ROUND_HALF_UP) {
            return self::add(
                $number->getIntegerPart(),
                $number->getIntegerRoundingMultiplier()
            );
        }

        if ($roundingMode === Money::ROUND_HALF_DOWN) {
            return self::add($number->getIntegerPart(), '0');
        }

        if ($roundingMode === Money::ROUND_HALF_EVEN) {
            if ($number->isCurrentEven()) {
                return self::add($number->getIntegerPart(), '0');
            }

            return self::add(
                $number->getIntegerPart(),
                $number->getIntegerRoundingMultiplier()
            );
        }

        if ($roundingMode === Money::ROUND_HALF_ODD) {
            if ($number->isCurrentEven()) {
                return self::add(
                    $number->getIntegerPart(),
                    $number->getIntegerRoundingMultiplier()
                );
            }

            return self::add($number->getIntegerPart(), '0');
        }

        if ($roundingMode === Money::ROUND_HALF_POSITIVE_INFINITY) {
            if ($number->isNegative()) {
                return self::add(
                    $number->getIntegerPart(),
                    '0'
                );
            }

            return self::add(
                $number->getIntegerPart(),
                $number->getIntegerRoundingMultiplier()
            );
        }

        if ($roundingMode === Money::ROUND_HALF_NEGATIVE_INFINITY) {
            if ($number->isNegative()) {
                return self::add(
                    $number->getIntegerPart(),
                    $number->getIntegerRoundingMultiplier()
                );
            }

            return self::add(
                $number->getIntegerPart(),
                '0'
            );
        }

        throw new CoreInvalidArgumentException('Unknown rounding mode');
    }

    /**
     * @psalm-return numeric-string
     *
     * @psalm-pure
     */
    private static function roundDigit(Number $number): string
    {
        if ($number->isCloserToNext()) {
            return self::add(
                $number->getIntegerPart(),
                $number->getIntegerRoundingMultiplier()
            );
        }

        return self::add($number->getIntegerPart(), '0');
    }

    /** @psalm-pure */
    public static function share(string $amount, string $ratio, string $total): string
    {
        return self::floor(self::divide(self::multiply($amount, $ratio), $total));
    }

    /** @psalm-pure */
    public static function mod(string $amount, string $divisor): string
    {
        if (self::compare($divisor, '0') === 0) {
            throw InvalidArgumentException::moduloByZero();
        }

        // gmp_mod() only calculates non-negative integers, so we use absolutes
        $remainder = gmp_mod(self::absolute($amount), self::absolute($divisor));

        // If the amount was negative, we negate the result of the modulus operation
        $amount = Number::fromString($amount);

        if ($amount->isNegative()) {
            $remainder = gmp_neg($remainder);
        }

        return gmp_strval($remainder);
    }
}
