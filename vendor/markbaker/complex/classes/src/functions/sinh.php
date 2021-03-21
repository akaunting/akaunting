<?php

/**
 *
 * Function code for the complex sinh() function
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPComplex)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Complex;

/**
 * Returns the hyperbolic sine of a complex number.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    Complex          The hyperbolic sine of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 */
function sinh($complex): Complex
{
    $complex = Complex::validateComplexArgument($complex);

    if ($complex->isReal()) {
        return new Complex(\sinh($complex->getReal()));
    }

    return new Complex(
        \sinh($complex->getReal()) * \cos($complex->getImaginary()),
        \cosh($complex->getReal()) * \sin($complex->getImaginary()),
        $complex->getSuffix()
    );
}
