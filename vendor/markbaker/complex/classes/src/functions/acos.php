<?php

/**
 *
 * Function code for the complex acos() function
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPComplex)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Complex;

/**
 * Returns the inverse cosine of a complex number.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    Complex          The inverse cosine of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 */
function acos($complex): Complex
{
    $complex = Complex::validateComplexArgument($complex);

    $square = clone $complex;
    $square = multiply($square, $complex);
    $invsqrt = new Complex(1.0);
    $invsqrt = subtract($invsqrt, $square);
    $invsqrt = sqrt($invsqrt);
    $adjust = new Complex(
        $complex->getReal() - $invsqrt->getImaginary(),
        $complex->getImaginary() + $invsqrt->getReal()
    );
    $log = ln($adjust);

    return new Complex(
        $log->getImaginary(),
        -1 * $log->getReal()
    );
}
