<?php

namespace Complex;

use InvalidArgumentException;

class Operations
{
    /**
     * Adds two or more complex numbers
     *
     * @param     array of string|integer|float|Complex    $complexValues   The numbers to add
     * @return    Complex
     */
    public static function add(...$complexValues): Complex
    {
        if (count($complexValues) < 2) {
            throw new \Exception('This function requires at least 2 arguments');
        }

        $base = array_shift($complexValues);
        $result = clone Complex::validateComplexArgument($base);

        foreach ($complexValues as $complex) {
            $complex = Complex::validateComplexArgument($complex);

            if ($result->isComplex() && $complex->isComplex() &&
                $result->getSuffix() !== $complex->getSuffix()) {
                throw new Exception('Suffix Mismatch');
            }

            $real = $result->getReal() + $complex->getReal();
            $imaginary = $result->getImaginary() + $complex->getImaginary();

            $result = new Complex(
                $real,
                $imaginary,
                ($imaginary == 0.0) ? null : max($result->getSuffix(), $complex->getSuffix())
            );
        }

        return $result;
    }

    /**
     * Divides two or more complex numbers
     *
     * @param     array of string|integer|float|Complex    $complexValues   The numbers to divide
     * @return    Complex
     */
    public static function divideby(...$complexValues): Complex
    {
        if (count($complexValues) < 2) {
            throw new \Exception('This function requires at least 2 arguments');
        }

        $base = array_shift($complexValues);
        $result = clone Complex::validateComplexArgument($base);

        foreach ($complexValues as $complex) {
            $complex = Complex::validateComplexArgument($complex);

            if ($result->isComplex() && $complex->isComplex() &&
                $result->getSuffix() !== $complex->getSuffix()) {
                throw new Exception('Suffix Mismatch');
            }
            if ($complex->getReal() == 0.0 && $complex->getImaginary() == 0.0) {
                throw new InvalidArgumentException('Division by zero');
            }

            $delta1 = ($result->getReal() * $complex->getReal()) +
                ($result->getImaginary() * $complex->getImaginary());
            $delta2 = ($result->getImaginary() * $complex->getReal()) -
                ($result->getReal() * $complex->getImaginary());
            $delta3 = ($complex->getReal() * $complex->getReal()) +
                ($complex->getImaginary() * $complex->getImaginary());

            $real = $delta1 / $delta3;
            $imaginary = $delta2 / $delta3;

            $result = new Complex(
                $real,
                $imaginary,
                ($imaginary == 0.0) ? null : max($result->getSuffix(), $complex->getSuffix())
            );
        }

        return $result;
    }

    /**
     * Divides two or more complex numbers
     *
     * @param     array of string|integer|float|Complex    $complexValues   The numbers to divide
     * @return    Complex
     */
    public static function divideinto(...$complexValues): Complex
    {
        if (count($complexValues) < 2) {
            throw new \Exception('This function requires at least 2 arguments');
        }

        $base = array_shift($complexValues);
        $result = clone Complex::validateComplexArgument($base);

        foreach ($complexValues as $complex) {
            $complex = Complex::validateComplexArgument($complex);

            if ($result->isComplex() && $complex->isComplex() &&
                $result->getSuffix() !== $complex->getSuffix()) {
                throw new Exception('Suffix Mismatch');
            }
            if ($result->getReal() == 0.0 && $result->getImaginary() == 0.0) {
                throw new InvalidArgumentException('Division by zero');
            }

            $delta1 = ($complex->getReal() * $result->getReal()) +
                ($complex->getImaginary() * $result->getImaginary());
            $delta2 = ($complex->getImaginary() * $result->getReal()) -
                ($complex->getReal() * $result->getImaginary());
            $delta3 = ($result->getReal() * $result->getReal()) +
                ($result->getImaginary() * $result->getImaginary());

            $real = $delta1 / $delta3;
            $imaginary = $delta2 / $delta3;

            $result = new Complex(
                $real,
                $imaginary,
                ($imaginary == 0.0) ? null : max($result->getSuffix(), $complex->getSuffix())
            );
        }

        return $result;
    }

    /**
     * Multiplies two or more complex numbers
     *
     * @param     array of string|integer|float|Complex    $complexValues   The numbers to multiply
     * @return    Complex
     */
    public static function multiply(...$complexValues): Complex
    {
        if (count($complexValues) < 2) {
            throw new \Exception('This function requires at least 2 arguments');
        }

        $base = array_shift($complexValues);
        $result = clone Complex::validateComplexArgument($base);

        foreach ($complexValues as $complex) {
            $complex = Complex::validateComplexArgument($complex);

            if ($result->isComplex() && $complex->isComplex() &&
                $result->getSuffix() !== $complex->getSuffix()) {
                throw new Exception('Suffix Mismatch');
            }

            $real = ($result->getReal() * $complex->getReal()) -
                ($result->getImaginary() * $complex->getImaginary());
            $imaginary = ($result->getReal() * $complex->getImaginary()) +
                ($result->getImaginary() * $complex->getReal());

            $result = new Complex(
                $real,
                $imaginary,
                ($imaginary == 0.0) ? null : max($result->getSuffix(), $complex->getSuffix())
            );
        }

        return $result;
    }

    /**
     * Subtracts two or more complex numbers
     *
     * @param     array of string|integer|float|Complex    $complexValues   The numbers to subtract
     * @return    Complex
     */
    public static function subtract(...$complexValues): Complex
    {
        if (count($complexValues) < 2) {
            throw new \Exception('This function requires at least 2 arguments');
        }

        $base = array_shift($complexValues);
        $result = clone Complex::validateComplexArgument($base);

        foreach ($complexValues as $complex) {
            $complex = Complex::validateComplexArgument($complex);

            if ($result->isComplex() && $complex->isComplex() &&
                $result->getSuffix() !== $complex->getSuffix()) {
                throw new Exception('Suffix Mismatch');
            }

            $real = $result->getReal() - $complex->getReal();
            $imaginary = $result->getImaginary() - $complex->getImaginary();

            $result = new Complex(
                $real,
                $imaginary,
                ($imaginary == 0.0) ? null : max($result->getSuffix(), $complex->getSuffix())
            );
        }

        return $result;
    }
}
