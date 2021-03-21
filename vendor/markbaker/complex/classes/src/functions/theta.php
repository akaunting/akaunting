<?php

/**
 *
 * Function code for the complex theta() function
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPComplex)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Complex;

/**
 * Returns the theta of a complex number.
 *   This is the angle in radians from the real axis to the representation of the number in polar coordinates.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    float            The theta value of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 */
function theta($complex): float
{
    $complex = Complex::validateComplexArgument($complex);

    if ($complex->getReal() == 0.0) {
        if ($complex->isReal()) {
            return 0.0;
        } elseif ($complex->getImaginary() < 0.0) {
            return M_PI / -2;
        }
        return M_PI / 2;
    } elseif ($complex->getReal() > 0.0) {
        return \atan($complex->getImaginary() / $complex->getReal());
    } elseif ($complex->getImaginary() < 0.0) {
        return -(M_PI - \atan(\abs($complex->getImaginary()) / \abs($complex->getReal())));
    }

    return M_PI - \atan($complex->getImaginary() / \abs($complex->getReal()));
}
