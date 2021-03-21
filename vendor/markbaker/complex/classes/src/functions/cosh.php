<?php

/**
 *
 * Function code for the complex cosh() function
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPComplex)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Complex;

/**
 * Returns the hyperbolic cosine of a complex number.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    Complex          The hyperbolic cosine of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 */
function cosh($complex): Complex
{
    $complex = Complex::validateComplexArgument($complex);

    if ($complex->isReal()) {
        return new Complex(\cosh($complex->getReal()));
    }

    return new Complex(
        \cosh($complex->getReal()) * \cos($complex->getImaginary()),
        \sinh($complex->getReal()) * \sin($complex->getImaginary()),
        $complex->getSuffix()
    );
}
