<?php

/**
 *
 * Function code for the complex exp() function
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPComplex)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Complex;

/**
 * Returns the exponential of a complex number.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    Complex          The exponential of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 */
function exp($complex): Complex
{
    $complex = Complex::validateComplexArgument($complex);

    if (($complex->getReal() == 0.0) && (\abs($complex->getImaginary()) == M_PI)) {
        return new Complex(-1.0, 0.0);
    }

    $rho = \exp($complex->getReal());
 
    return new Complex(
        $rho * \cos($complex->getImaginary()),
        $rho * \sin($complex->getImaginary()),
        $complex->getSuffix()
    );
}
