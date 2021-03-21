<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2017, Hoa community. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace Hoa\Math;

/**
 * Class \Hoa\Math\Context.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Context
{
    /**
     * List of supported functions: identifier => values as callable.
     *
     * @var \ArrayObject
     */
    protected $_functions = null;

    /**
     * List of supported constants: identifier => values.
     *
     * @var \ArrayObject
     */
    protected $_constants = null;

    /**
     * List of supported variables: identifier => values as callable.
     *
     * @var \ArrayObject
     */
    protected $_variables = null;



    /**
     * Initialize constants and functions.
     *
     */
    public function __construct()
    {
        $this->initializeConstants();
        $this->initializeFunctions();
        $this->initializeVariables();

        return;
    }

    /**
     * Add a constant.
     *
     * @param   string  $name     Constant name.
     * @param   mixed   $value    Value.
     * @return  void
     * @throws  \Hoa\Math\Exception\AlreadyDefinedConstant
     */
    public function addConstant($name, $value)
    {
        if (true === $this->_constants->offsetExists($name)) {
            throw new Exception\AlreadyDefinedConstant(
                'Constant %s is already defined.',
                0,
                $name
            );
        }

        $this->_constants[$name] = $value;

        return;
    }

    /**
     * Get a constant.
     *
     * @param   string  $name    Constant name.
     * @return  mixed
     * @throws  \Hoa\Math\Exception\UnknownConstant
     */
    public function getConstant($name)
    {
        if (false === $this->_constants->offsetExists($name)) {
            throw new Exception\UnknownConstant(
                'Constant %s does not exist.',
                1,
                $name
            );
        }

        return $this->_constants[$name];
    }

    /**
     * Get constants.
     *
     * @return  \ArrayObject
     */
    public function getConstants()
    {
        return $this->_constants;
    }

    /**
     * Add a function.
     *
     * @param   string  $name        Function name.
     * @param   mixed   $callable    Callable.
     * @return  void
     * @throws  \Hoa\Math\Exception\UnknownFunction
     */
    public function addFunction($name, $callable = null)
    {
        if (null === $callable) {
            if (false === function_exists($name)) {
                throw new Exception\UnknownFunction(
                    'Function %s does not exist, cannot add it.',
                    2,
                    $name
                );
            }

            $callable = $name;
        }

        $this->_functions[$name] = xcallable($callable);

        return;
    }

    /**
     * Get a function.
     *
     * @param   string  $name    Function name.
     * @return  \Hoa\Consistency\Xcallable
     * @throws  \Hoa\Math\Exception\UnknownFunction
     */
    public function getFunction($name)
    {
        if (false === $this->_functions->offsetExists($name)) {
            throw new Exception\UnknownFunction(
                'Function %s does not exist.',
                3,
                $name
            );
        }

        return $this->_functions[$name];
    }

    /**
     * Get functions.
     *
     * @return  \ArrayObject
     */
    public function getFunctions()
    {
        return $this->_functions;
    }

    /**
     * Add a variable.
     *
     * @param   string    $name        Variable name.
     * @param   callable  $callable    Callable.
     * @return  void
     */
    public function addVariable($name, callable $callable)
    {
        $this->_variables[$name] = xcallable($callable);

        return;
    }

    /**
     * Get a variable.
     *
     * @param   string   $name    Variable name.
     * @return  callable
     * @throws  \Hoa\Math\Exception\UnknownVariable
     */
    public function getVariable($name)
    {
        if (false === $this->_variables->offsetExists($name)) {
            throw new Exception\UnknownVariable(
                'Variable %s does not exist.',
                4,
                $name
            );
        }

        return $this->_variables[$name]($this);
    }

    /**
     * Get variables.
     *
     * @return \ArrayObject
     */
    public function getVariables()
    {
        return $this->_variables;
    }

    /**
     * Initialize constants mapping.
     *
     * @return void
     */
    protected function initializeConstants()
    {
        static $_constants = null;

        if (null === $_constants) {
            $_constants = new \ArrayObject([
                'PI'               => M_PI,
                'PI_2'             => M_PI_2,
                'PI_4'             => M_PI_4,
                'E'                => M_E,
                'SQRT_PI'          => M_SQRTPI,
                'SQRT_2'           => M_SQRT2,
                'SQRT_3'           => M_SQRT3,
                'LN_PI'            => M_LNPI,
                'LOG_2E'           => M_LOG2E,
                'LOG_10E'          => M_LOG10E,
                'LN_2'             => M_LN2,
                'LN_10'            => M_LN10,
                'ONE_OVER_PI'      => M_1_PI,
                'TWO_OVER_PI'      => M_2_PI,
                'TWO_OVER_SQRT_PI' => M_2_SQRTPI,
                'ONE_OVER_SQRT_2'  => M_SQRT1_2,
                'EULER'            => M_EULER,
                'INFINITE'         => INF
            ]);
        }

        $this->_constants = $_constants;

        return;
    }

    /**
     * Initialize functions mapping.
     *
     * @return void
     */
    protected function initializeFunctions()
    {
        static $_functions = null;

        if (null === $_functions) {
            $average = function () {
                $arguments = func_get_args();

                return array_sum($arguments) / count($arguments);
            };

            $_functions = new \ArrayObject([
                'abs'     => xcallable('abs'),
                'acos'    => xcallable('acos'),
                'asin'    => xcallable('asin'),
                'atan'    => xcallable('atan'),
                'average' => xcallable($average),
                'avg'     => xcallable($average),
                'ceil'    => xcallable('ceil'),
                'cos'     => xcallable('cos'),
                'count'   => xcallable(function () { return count(func_get_args()); }),
                'deg2rad' => xcallable('deg2rad'),
                'exp'     => xcallable('exp'),
                'floor'   => xcallable('floor'),
                'ln'      => xcallable('log'),
                'log'     => xcallable(function ($value, $base = 10) { return log($value, $base); }),
                'max'     => xcallable('max'),
                'min'     => xcallable('min'),
                'pow'     => xcallable('pow'),
                'rad2deg' => xcallable('rad2deg'),
                'round'   => xcallable(function ($value, $precision = 0) { return round($value, $precision); }),
                'sin'     => xcallable('sin'),
                'sqrt'    => xcallable('sqrt'),
                'sum'     => xcallable(function () { return array_sum(func_get_args()); }),
                'tan'     => xcallable('tan')
            ]);
        }

        $this->_functions = $_functions;

        return;
    }

    /**
     * Initialize variables mapping.
     *
     * @return void
     */
    protected function initializeVariables()
    {
        if (null === $this->_variables) {
            $this->_variables = new \ArrayObject();
        }

        return;
    }
}
