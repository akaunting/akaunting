<?php

/**
 *
 * Function code for the complex tanh() function
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPComplex)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Complex;

/**
 * Returns the hyperbolic tangent of a complex number.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    Complex          The hyperbolic tangent of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 * @throws    \InvalidArgumentException    If function would result in a division by zero
 */
function tanh($complex): Complex
{
    $complex = Complex::validateComplexArgument($complex);
    $real = $complex->getReal();
    $imaginary = $complex->getImaginary();
    $divisor = \cos($imaginary) * \cos($imaginary) + \sinh($real) * \sinh($real);
    if ($divisor == 0.0) {
        throw new \InvalidArgumentException('Division by zero');
    }

    return new Complex(
        \sinh($real) * \cosh($real) / $divisor,
        0.5 * \sin(2 * $imaginary) / $divisor,
        $complex->getSuffix()
    );
}
