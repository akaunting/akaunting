<?php

/**
 *
 * Function code for the complex subtraction operation
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPComplex)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Complex;

/**
 * Subtracts two or more complex numbers
 *
 * @param     array of string|integer|float|Complex    $complexValues   The numbers to subtract
 * @return    Complex
 */
function subtract(...$complexValues): Complex
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
