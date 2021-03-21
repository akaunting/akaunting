<?php

/**
 *
 * Function code for the complex argument() function
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPComplex)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Complex;

/**
 * Returns the argument of a complex number.
 * Also known as the theta of the complex number, i.e. the angle in radians
 *   from the real axis to the representation of the number in polar coordinates.
 *
 * This function is a synonym for theta()
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    float            The argument (or theta) value of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 *
 * @see    theta
 */
function argument($complex): float
{
    return theta($complex);
}
