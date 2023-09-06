<?php

namespace Complex;

use InvalidArgumentException;

class Functions
{
    /**
     * Returns the absolute value (modulus) of a complex number.
     * Also known as the rho of the complex number, i.e. the distance/radius
     *   from the centrepoint to the representation of the number in polar coordinates.
     *
     * This function is a synonym for rho()
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    float            The absolute (or rho) value of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     *
     * @see    rho
     *
     */
    public static function abs($complex): float
    {
        return self::rho($complex);
    }

    /**
     * Returns the inverse cosine of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The inverse cosine of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     */
    public static function acos($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        $invsqrt = self::sqrt(Operations::subtract(1, Operations::multiply($complex, $complex)));
        $adjust = new Complex(
            $complex->getReal() - $invsqrt->getImaginary(),
            $complex->getImaginary() + $invsqrt->getReal()
        );
        $log = self::ln($adjust);

        return new Complex(
            $log->getImaginary(),
            -1 * $log->getReal()
        );
    }

    /**
     * Returns the inverse hyperbolic cosine of a complex number.
     *
     * Formula from Wolfram Alpha:
     *   cosh^(-1)z = ln(z + sqrt(z + 1) sqrt(z - 1)).
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The inverse hyperbolic cosine of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     */
    public static function acosh($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if ($complex->isReal() && ($complex->getReal() > 1)) {
            return new Complex(\acosh($complex->getReal()));
        }

        $acosh = self::ln(
            Operations::add(
                $complex,
                Operations::multiply(
                    self::sqrt(Operations::add($complex, 1)),
                    self::sqrt(Operations::subtract($complex, 1))
                )
            )
        );

        return $acosh;
    }

    /**
     * Returns the inverse cotangent of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The inverse cotangent of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    \InvalidArgumentException    If function would result in a division by zero
     */
    public static function acot($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        return self::atan(self::inverse($complex));
    }

    /**
     * Returns the inverse hyperbolic cotangent of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The inverse hyperbolic cotangent of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    \InvalidArgumentException    If function would result in a division by zero
     */
    public static function acoth($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        return self::atanh(self::inverse($complex));
    }

    /**
     * Returns the inverse cosecant of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The inverse cosecant of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    \InvalidArgumentException    If function would result in a division by zero
     */
    public static function acsc($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if ($complex->getReal() == 0.0 && $complex->getImaginary() == 0.0) {
            return new Complex(INF);
        }

        return self::asin(self::inverse($complex));
    }

    /**
     * Returns the inverse hyperbolic cosecant of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The inverse hyperbolic cosecant of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    \InvalidArgumentException    If function would result in a division by zero
     */
    public static function acsch($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if ($complex->getReal() == 0.0 && $complex->getImaginary() == 0.0) {
            return new Complex(INF);
        }

        return self::asinh(self::inverse($complex));
    }

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
    public static function argument($complex): float
    {
        return self::theta($complex);
    }

    /**
     * Returns the inverse secant of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The inverse secant of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    \InvalidArgumentException    If function would result in a division by zero
     */
    public static function asec($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if ($complex->getReal() == 0.0 && $complex->getImaginary() == 0.0) {
            return new Complex(INF);
        }

        return self::acos(self::inverse($complex));
    }

    /**
     * Returns the inverse hyperbolic secant of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The inverse hyperbolic secant of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    \InvalidArgumentException    If function would result in a division by zero
     */
    public static function asech($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if ($complex->getReal() == 0.0 && $complex->getImaginary() == 0.0) {
            return new Complex(INF);
        }

        return self::acosh(self::inverse($complex));
    }

    /**
     * Returns the inverse sine of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The inverse sine of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     */
    public static function asin($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        $invsqrt = self::sqrt(Operations::subtract(1, Operations::multiply($complex, $complex)));
        $adjust = new Complex(
            $invsqrt->getReal() - $complex->getImaginary(),
            $invsqrt->getImaginary() + $complex->getReal()
        );
        $log = self::ln($adjust);

        return new Complex(
            $log->getImaginary(),
            -1 * $log->getReal()
        );
    }

    /**
     * Returns the inverse hyperbolic sine of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The inverse hyperbolic sine of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     */
    public static function asinh($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if ($complex->isReal() && ($complex->getReal() > 1)) {
            return new Complex(\asinh($complex->getReal()));
        }

        $asinh = clone $complex;
        $asinh = $asinh->reverse()
            ->invertReal();
        $asinh = self::asin($asinh);

        return $asinh->reverse()
            ->invertImaginary();
    }

    /**
     * Returns the inverse tangent of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The inverse tangent of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    \InvalidArgumentException    If function would result in a division by zero
     */
    public static function atan($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if ($complex->isReal()) {
            return new Complex(\atan($complex->getReal()));
        }

        $t1Value = new Complex(-1 * $complex->getImaginary(), $complex->getReal());
        $uValue = new Complex(1, 0);

        $d1Value = clone $uValue;
        $d1Value = Operations::subtract($d1Value, $t1Value);
        $d2Value = Operations::add($t1Value, $uValue);
        $uResult = $d1Value->divideBy($d2Value);
        $uResult = self::ln($uResult);

        $realMultiplier = -0.5;
        $imaginaryMultiplier = 0.5;

        if (abs($uResult->getImaginary()) === M_PI) {
            // If we have an imaginary value at the max or min (PI or -PI), then we need to ensure
            //    that the primary is assigned for the correct quadrant.
            $realMultiplier = (
                ($uResult->getImaginary() === M_PI && $uResult->getReal() > 0.0) ||
                ($uResult->getImaginary() === -M_PI && $uResult->getReal() < 0.0)
            ) ? 0.5 : -0.5;
        }

        return new Complex(
            $uResult->getImaginary() * $realMultiplier,
            $uResult->getReal() * $imaginaryMultiplier,
            $complex->getSuffix()
        );
    }

    /**
     * Returns the inverse hyperbolic tangent of a complex number.
     *
     * Formula from Wolfram Alpha:
     *  tanh^(-1)z = 1/2 [ln(1 + z) - ln(1 - z)].
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The inverse hyperbolic tangent of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     */
    public static function atanh($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if ($complex->isReal()) {
            $real = $complex->getReal();
            if ($real >= -1.0 && $real <= 1.0) {
                return new Complex(\atanh($real));
            } else {
                return new Complex(\atanh(1 / $real), (($real < 0.0) ? M_PI_2 : -1 * M_PI_2));
            }
        }

        $atanh = Operations::multiply(
            Operations::subtract(
                self::ln(Operations::add(1.0, $complex)),
                self::ln(Operations::subtract(1.0, $complex))
            ),
            0.5
        );

        return $atanh;
    }

    /**
     * Returns the complex conjugate of a complex number
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The conjugate of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     */
    public static function conjugate($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        return new Complex(
            $complex->getReal(),
            -1 * $complex->getImaginary(),
            $complex->getSuffix()
        );
    }

    /**
     * Returns the cosine of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The cosine of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     */
    public static function cos($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if ($complex->isReal()) {
            return new Complex(\cos($complex->getReal()));
        }

        return self::conjugate(
            new Complex(
                \cos($complex->getReal()) * \cosh($complex->getImaginary()),
                \sin($complex->getReal()) * \sinh($complex->getImaginary()),
                $complex->getSuffix()
            )
        );
    }

    /**
     * Returns the hyperbolic cosine of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The hyperbolic cosine of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     */
    public static function cosh($complex): Complex
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

    /**
     * Returns the cotangent of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The cotangent of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    \InvalidArgumentException    If function would result in a division by zero
     */
    public static function cot($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if ($complex->getReal() == 0.0 && $complex->getImaginary() == 0.0) {
            return new Complex(INF);
        }

        return self::inverse(self::tan($complex));
    }

    /**
     * Returns the hyperbolic cotangent of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The hyperbolic cotangent of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    \InvalidArgumentException    If function would result in a division by zero
     */
    public static function coth($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        return self::inverse(self::tanh($complex));
    }

    /**
     * Returns the cosecant of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The cosecant of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    \InvalidArgumentException    If function would result in a division by zero
     */
    public static function csc($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if ($complex->getReal() == 0.0 && $complex->getImaginary() == 0.0) {
            return new Complex(INF);
        }

        return self::inverse(self::sin($complex));
    }

    /**
     * Returns the hyperbolic cosecant of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The hyperbolic cosecant of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    \InvalidArgumentException    If function would result in a division by zero
     */
    public static function csch($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if ($complex->getReal() == 0.0 && $complex->getImaginary() == 0.0) {
            return new Complex(INF);
        }

        return self::inverse(self::sinh($complex));
    }

    /**
     * Returns the exponential of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The exponential of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     */
    public static function exp($complex): Complex
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

    /**
     * Returns the inverse of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The inverse of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    InvalidArgumentException    If function would result in a division by zero
     */
    public static function inverse($complex): Complex
    {
        $complex = clone Complex::validateComplexArgument($complex);

        if ($complex->getReal() == 0.0 && $complex->getImaginary() == 0.0) {
            throw new InvalidArgumentException('Division by zero');
        }

        return $complex->divideInto(1.0);
    }

    /**
     * Returns the natural logarithm of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The natural logarithm of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    InvalidArgumentException  If the real and the imaginary parts are both zero
     */
    public static function ln($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if (($complex->getReal() == 0.0) && ($complex->getImaginary() == 0.0)) {
            throw new InvalidArgumentException();
        }

        return new Complex(
            \log(self::rho($complex)),
            self::theta($complex),
            $complex->getSuffix()
        );
    }

    /**
     * Returns the base-2 logarithm of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The base-2 logarithm of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    InvalidArgumentException  If the real and the imaginary parts are both zero
     */
    public static function log2($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if (($complex->getReal() == 0.0) && ($complex->getImaginary() == 0.0)) {
            throw new InvalidArgumentException();
        } elseif (($complex->getReal() > 0.0) && ($complex->getImaginary() == 0.0)) {
            return new Complex(\log($complex->getReal(), 2), 0.0, $complex->getSuffix());
        }

        return self::ln($complex)
            ->multiply(\log(Complex::EULER, 2));
    }

    /**
     * Returns the common logarithm (base 10) of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The common logarithm (base 10) of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    InvalidArgumentException  If the real and the imaginary parts are both zero
     */
    public static function log10($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if (($complex->getReal() == 0.0) && ($complex->getImaginary() == 0.0)) {
            throw new InvalidArgumentException();
        } elseif (($complex->getReal() > 0.0) && ($complex->getImaginary() == 0.0)) {
            return new Complex(\log10($complex->getReal()), 0.0, $complex->getSuffix());
        }

        return self::ln($complex)
            ->multiply(\log10(Complex::EULER));
    }

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
    public static function negative($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        return new Complex(
            -1 * $complex->getReal(),
            -1 * $complex->getImaginary(),
            $complex->getSuffix()
        );
    }

    /**
     * Returns a complex number raised to a power.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @param     float|integer    $power      The power to raise this value to
     * @return    Complex          The complex argument raised to the real power.
     * @throws    Exception        If the power argument isn't a valid real
     */
    public static function pow($complex, $power): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if (!is_numeric($power)) {
            throw new Exception('Power argument must be a real number');
        }

        if ($complex->getImaginary() == 0.0 && $complex->getReal() >= 0.0) {
            return new Complex(\pow($complex->getReal(), $power));
        }

        $rValue = \sqrt(($complex->getReal() * $complex->getReal()) + ($complex->getImaginary() * $complex->getImaginary()));
        $rPower = \pow($rValue, $power);
        $theta = $complex->argument() * $power;
        if ($theta == 0) {
            return new Complex(1);
        }

        return new Complex($rPower * \cos($theta), $rPower * \sin($theta), $complex->getSuffix());
    }

    /**
     * Returns the rho of a complex number.
     * This is the distance/radius from the centrepoint to the representation of the number in polar coordinates.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    float            The rho value of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     */
    public static function rho($complex): float
    {
        $complex = Complex::validateComplexArgument($complex);

        return \sqrt(
            ($complex->getReal() * $complex->getReal()) +
            ($complex->getImaginary() * $complex->getImaginary())
        );
    }

    /**
     * Returns the secant of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The secant of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    \InvalidArgumentException    If function would result in a division by zero
     */
    public static function sec($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        return self::inverse(self::cos($complex));
    }

    /**
     * Returns the hyperbolic secant of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The hyperbolic secant of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    \InvalidArgumentException    If function would result in a division by zero
     */
    public static function sech($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        return self::inverse(self::cosh($complex));
    }

    /**
     * Returns the sine of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The sine of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     */
    public static function sin($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if ($complex->isReal()) {
            return new Complex(\sin($complex->getReal()));
        }

        return new Complex(
            \sin($complex->getReal()) * \cosh($complex->getImaginary()),
            \cos($complex->getReal()) * \sinh($complex->getImaginary()),
            $complex->getSuffix()
        );
    }

    /**
     * Returns the hyperbolic sine of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The hyperbolic sine of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     */
    public static function sinh($complex): Complex
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

    /**
     * Returns the square root of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The Square root of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     */
    public static function sqrt($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        $theta = self::theta($complex);
        $delta1 = \cos($theta / 2);
        $delta2 = \sin($theta / 2);
        $rho = \sqrt(self::rho($complex));

        return new Complex($delta1 * $rho, $delta2 * $rho, $complex->getSuffix());
    }

    /**
     * Returns the tangent of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The tangent of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    InvalidArgumentException    If function would result in a division by zero
     */
    public static function tan($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);

        if ($complex->isReal()) {
            return new Complex(\tan($complex->getReal()));
        }

        $real = $complex->getReal();
        $imaginary = $complex->getImaginary();
        $divisor = 1 + \pow(\tan($real), 2) * \pow(\tanh($imaginary), 2);
        if ($divisor == 0.0) {
            throw new InvalidArgumentException('Division by zero');
        }

        return new Complex(
            \pow(self::sech($imaginary)->getReal(), 2) * \tan($real) / $divisor,
            \pow(self::sec($real)->getReal(), 2) * \tanh($imaginary) / $divisor,
            $complex->getSuffix()
        );
    }

    /**
     * Returns the hyperbolic tangent of a complex number.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    Complex          The hyperbolic tangent of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     * @throws    \InvalidArgumentException    If function would result in a division by zero
     */
    public static function tanh($complex): Complex
    {
        $complex = Complex::validateComplexArgument($complex);
        $real = $complex->getReal();
        $imaginary = $complex->getImaginary();
        $divisor = \cos($imaginary) * \cos($imaginary) + \sinh($real) * \sinh($real);
        if ($divisor == 0.0) {
            throw new InvalidArgumentException('Division by zero');
        }

        return new Complex(
            \sinh($real) * \cosh($real) / $divisor,
            0.5 * \sin(2 * $imaginary) / $divisor,
            $complex->getSuffix()
        );
    }

    /**
     * Returns the theta of a complex number.
     *   This is the angle in radians from the real axis to the representation of the number in polar coordinates.
     *
     * @param     Complex|mixed    $complex    Complex number or a numeric value.
     * @return    float            The theta value of the complex argument.
     * @throws    Exception        If argument isn't a valid real or complex number.
     */
    public static function theta($complex): float
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
}
