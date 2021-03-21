<?php

/**
 *
 * Function code for the complex division operation
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPComplex)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Complex;

/**
 * Divides two or more complex numbers
 *
 * @param     array of string|integer|float|Complex    $complexValues   The numbers to divide
 * @return    Complex
 */
function divideinto(...$complexValues): Complex
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
            throw new \InvalidArgumentException('Division by zero');
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
