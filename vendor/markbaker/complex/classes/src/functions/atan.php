<?php

/**
 *
 * Function code for the complex atan() function
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPComplex)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Complex;

//include_once 'Math/Complex.php';
//include_once 'Math/ComplexOp.php';

/**
 * Returns the inverse tangent of a complex number.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    Complex          The inverse tangent of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 * @throws    \InvalidArgumentException    If function would result in a division by zero
 */
function atan($complex): Complex
{
    $complex = Complex::validateComplexArgument($complex);

    if ($complex->isReal()) {
        return new Complex(\atan($complex->getReal()));
    }

    $t1Value = new Complex(-1 * $complex->getImaginary(), $complex->getReal());
    $uValue = new Complex(1, 0);

    $d1Value = clone $uValue;
    $d1Value = subtract($d1Value, $t1Value);
    $d2Value = add($t1Value, $uValue);
    $uResult = $d1Value->divideBy($d2Value);
    $uResult = ln($uResult);

    return new Complex(
        (($uResult->getImaginary() == M_PI) ? -M_PI : $uResult->getImaginary()) * -0.5,
        $uResult->getReal() * 0.5,
        $complex->getSuffix()
    );
}
