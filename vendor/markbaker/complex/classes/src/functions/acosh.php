<?php

/**
 *
 * Function code for the complex acosh() function
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPComplex)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Complex;

/**
 * Returns the inverse hyperbolic cosine of a complex number.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    Complex          The inverse hyperbolic cosine of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 */
function acosh($complex)
{
    $complex = Complex::validateComplexArgument($complex);

    if ($complex->isReal() && ($complex->getReal() > 1)) {
        return new Complex(\acosh($complex->getReal()));
    }

    $acosh = acos($complex)
        ->reverse();
    if ($acosh->getReal() < 0.0) {
        $acosh = $acosh->invertReal();
    }

    return $acosh;
}
