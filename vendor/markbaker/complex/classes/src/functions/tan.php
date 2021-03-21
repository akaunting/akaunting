<?php

/**
 *
 * Function code for the complex tan() function
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPComplex)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Complex;

/**
 * Returns the tangent of a complex number.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    Complex          The tangent of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 * @throws    \InvalidArgumentException    If function would result in a division by zero
 */
function tan($complex): Complex
{
    $complex = Complex::validateComplexArgument($complex);

    if ($complex->isReal()) {
        return new Complex(\tan($complex->getReal()));
    }

    $real = $complex->getReal();
    $imaginary = $complex->getImaginary();
    $divisor = 1 + \pow(\tan($real), 2) * \pow(\tanh($imaginary), 2);
    if ($divisor == 0.0) {
        throw new \InvalidArgumentException('Division by zero');
    }

    return new Complex(
        \pow(sech($imaginary)->getReal(), 2) * \tan($real) / $divisor,
        \pow(sec($real)->getReal(), 2) * \tanh($imaginary) / $divisor,
        $complex->getSuffix()
    );
}
