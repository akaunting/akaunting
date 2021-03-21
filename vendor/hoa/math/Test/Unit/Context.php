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

namespace Hoa\Math\Test\Unit;

use Hoa\Math\Context as CUT;
use Hoa\Test;

/**
 * Class \Hoa\Math\Test\Unit\Context.
 *
 * Test suite of the Hoa\Math\Context class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Context extends Test\Unit\Suite
{
    public function case_context_has_no_predefined_variable()
    {
        $this
            ->given($context = new CUT())
            ->when($result = $context->getVariables())
            ->then
                ->object($result)
                    ->isInstanceOf('ArrayObject')
                ->array(iterator_to_array($result))
                    ->isEmpty();
    }

    public function case_context_exception_when_getting_unknown_variable()
    {
        $this
            ->given(
                $name    = 'foo',
                $context = new CUT()
            )
            ->then
                ->exception(function () use ($context, $name) {
                    $context->getVariable($name);
                })
                    ->isInstanceOf('Hoa\Math\Exception\UnknownVariable');
    }

    public function case_context_returns_variable_value()
    {
        $this
            ->given(
                $name     = 'foo',
                $value    = 42,
                $callable = function () use ($value) { return $value; },
                $context  = new CUT(),
                $context->addVariable($name, $callable)
            )
            ->when($result = $context->getVariable($name))
            ->then
                ->integer($result)
                    ->isEqualTo($value);
    }

    public function case_context_has_predefined_constants()
    {
        $this
            ->given($context = new CUT())
            ->when($result = $context->getConstants())
            ->then
                ->object($result)
                    ->isInstanceOf('ArrayObject')
                ->array(iterator_to_array($result))
                    ->isEqualTo([
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

    public function case_context_exception_when_getting_unknown_constant()
    {
        $this
            ->given(
                $name    = 'FOO',
                $context = new CUT()
            )
            ->then
                ->exception(function () use ($context, $name) {
                    $context->getConstant($name);
                })
                    ->isInstanceOf('Hoa\Math\Exception\UnknownConstant');
    }

    public function case_context_exception_when_setting_already_defined_constant()
    {
        $this
            ->given(
                $name    = 'PI',
                $context = new CUT()
            )
            ->then
                ->exception(function () use ($context, $name) {
                    $context->addConstant($name, 42);
                })
                    ->isInstanceOf('Hoa\Math\Exception\AlreadyDefinedConstant');
    }

    public function case_context_returns_constant_value()
    {
        $this
            ->given(
                $name     = 'FOO',
                $value    = 42,
                $context  = new CUT(),
                $context->addConstant($name, $value)
            )
            ->when($result = $context->getConstant($name))
            ->then
                ->variable($result)
                    ->isEqualTo($value);
    }

    public function case_context_has_predefined_functions()
    {
        $this
            ->given($context = new CUT())
            ->when($result = $context->getFunctions())
            ->then
                ->object($result)
                    ->isInstanceOf('ArrayObject')
                ->array(iterator_to_array($result))
                    ->hasSize(23)
                    ->hasKey('abs')
                    ->hasKey('acos')
                    ->hasKey('asin')
                    ->hasKey('atan')
                    ->hasKey('average')
                    ->hasKey('avg')
                    ->hasKey('ceil')
                    ->hasKey('cos')
                    ->hasKey('count')
                    ->hasKey('deg2rad')
                    ->hasKey('exp')
                    ->hasKey('floor')
                    ->hasKey('ln')
                    ->hasKey('log')
                    ->hasKey('max')
                    ->hasKey('min')
                    ->hasKey('pow')
                    ->hasKey('rad2deg')
                    ->hasKey('round')
                    ->hasKey('round')
                    ->hasKey('sin')
                    ->hasKey('sqrt')
                    ->hasKey('sum')
                    ->hasKey('tan');
    }

    public function case_context_exception_when_getting_unknown_function()
    {
        $this
            ->given(
                $name    = 'foo',
                $context = new CUT()
            )
            ->then
                ->exception(function () use ($context, $name) {
                    $context->getFunction($name);
                })
                    ->isInstanceOf('Hoa\Math\Exception\UnknownFunction');
    }

    public function case_context_exception_when_setting_unknown_function()
    {
        $this
            ->given(
                $name    = 'foo',
                $context = new CUT()
            )
            ->then
                ->exception(function () use ($context, $name) {
                    $context->addFunction($name);
                })
                    ->isInstanceOf('Hoa\Math\Exception\UnknownFunction');
    }

    public function case_context_returns_function_callable()
    {
        $this
            ->given(
                $name     = 'foo',
                $callable = function () {},
                $context  = new CUT(),
                $context->addFunction($name, $callable)
            )
            ->when($result = $context->getFunction($name))
            ->then
                ->object($result)
                    ->isInstanceOf('Hoa\Consistency\Xcallable');
    }

    public function case_context_returns_the_right_function_callable()
    {
        $this
            ->given(
                $name     = 'foo',
                $value    = 42,
                $callable = function () use ($value) { return $value; },
                $context  = new CUT(),
                $context->addFunction($name, $callable)
            )
            ->when($result = $context->getFunction($name))
            ->then
                ->integer($result())
                    ->isEqualTo($value);
    }
}
