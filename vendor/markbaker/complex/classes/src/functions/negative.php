<?php

/**
 *
 * Function code for the complex negative() function
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPComplex)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Complex;

/**
 * Returns the negative of a complex number.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    Complex          The negative value of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 *
 * @see    rho
 *
 */
function negative($complex): Complex
{
    $complex = Complex::validateComplexArgument($complex);

    return new Complex(
        -1 * $complex->getReal(),
        -1 * $complex->getImaginary(),
        $complex->getSuffix()
    );
}
