<?php

/**
 *
 * Function code for the complex multiplication operation
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPComplex)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Complex;

/**
 * Multiplies two or more complex numbers
 *
 * @param     array of string|integer|float|Complex    $complexValues   The numbers to multiply
 * @return    Complex
 */
function multiply(...$complexValues): Complex
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
