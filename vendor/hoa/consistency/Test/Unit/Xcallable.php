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

namespace Hoa\Consistency\Test\Unit;

use Hoa\Consistency\Xcallable as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Consistency\Test\Unit\Xcallable.
 *
 * Test suite of the xcallable class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Xcallable extends Test\Unit\Suite
{
    public function case_form_function()
    {
        $this
            ->when($result = new SUT('strtoupper'))
            ->then
                ->string($result('foo'))
                    ->isEqualTo('FOO')
                ->string($result->getValidCallback())
                    ->isEqualTo('strtoupper')
                ->string($result->getHash())
                    ->isEqualTo('function#strtoupper')
                    ->isEqualTo($result . '')
                ->object($reflection = $result->getReflection())
                    ->isInstanceOf('ReflectionFunction')
                ->string($reflection->getName())
                    ->isEqualTo('strtoupper');
    }

    public function case_form_class___method()
    {
        $this
            ->when($result = new SUT(__CLASS__ . '::strtoupper'))
            ->then
                ->string($result('foo'))
                    ->isEqualTo('FOO')
                ->array($result->getValidCallback())
                    ->isEqualTo([__CLASS__, 'strtoupper'])
                ->string($result->getHash())
                    ->isEqualTo('class#' . __CLASS__ . '::strtoupper')
                    ->isEqualTo($result . '')
                ->object($reflection = $result->getReflection())
                    ->isInstanceOf('ReflectionMethod')
                ->string($reflection->getName())
                    ->isEqualTo('strtoupper');
    }

    public function case_form_class_method()
    {
        $this
            ->when($result = new SUT(__CLASS__, 'strtoupper'))
            ->then
                ->string($result('foo'))
                    ->isEqualTo('FOO')
                ->array($result->getValidCallback())
                    ->isEqualTo([__CLASS__, 'strtoupper'])
                ->string($result->getHash())
                    ->isEqualTo('class#' . __CLASS__ . '::strtoupper')
                    ->isEqualTo($result . '')
                ->object($reflection = $result->getReflection())
                    ->isInstanceOf('ReflectionMethod')
                ->string($reflection->getName())
                    ->isEqualTo('strtoupper');
    }

    public function case_form_object_method()
    {
        $this
            ->when($result = new SUT($this, 'strtolower'))
            ->then
                ->string($result('FOO'))
                    ->isEqualTo('foo')
                ->array($result->getValidCallback())
                    ->isEqualTo([$this, 'strtolower'])
                ->string($result->getHash())
                    ->matches(
                        '/^object\([^:]+\)#' .
                        preg_quote(__CLASS__) .
                        '::strtolower$/'
                    )
                    ->isEqualTo($result . '')
                ->object($reflection = $result->getReflection())
                    ->isInstanceOf('ReflectionMethod')
                ->string($reflection->getName())
                    ->isEqualTo('strtolower');
    }

    public function case_form_object_invoke()
    {
        $this
            ->when($result = new SUT($this))
            ->then
                ->string($result('foo'))
                    ->isEqualTo('FOO')
                ->array($result->getValidCallback())
                    ->isEqualTo([$this, '__invoke'])
                ->string($result->getHash())
                    ->matches(
                        '/^object\([^:]+\)#' .
                        preg_quote(__CLASS__) .
                        '::__invoke$/'
                    )
                    ->isEqualTo($result . '')
                ->object($reflection = $result->getReflection())
                    ->isInstanceOf('ReflectionMethod')
                ->string($reflection->getName())
                    ->isEqualTo('__invoke');
    }

    public function case_form_closure()
    {
        $this
            ->given(
                $closure = function ($string) {
                    return strtoupper($string);
                }
            )
            ->when($result = new SUT($closure))
            ->then
                ->string($result('foo'))
                    ->isEqualTo('FOO')
                ->object($result->getValidCallback())
                    ->isIdenticalTo($closure)
                ->string($result->getHash())
                    ->matches('/^closure\([^:]+\)$/')
                    ->isEqualTo($result . '')
                ->object($reflection = $result->getReflection())
                    ->isInstanceOf('ReflectionFunction')
                ->string($reflection->getName())
                    ->isEqualTo('Hoa\Consistency\Test\Unit\{closure}');
    }

    public function case_form_array_of_class_method()
    {
        $this
            ->when($result = new SUT([__CLASS__, 'strtoupper']))
            ->then
                ->string($result('foo'))
                    ->isEqualTo('FOO')
                ->array($result->getValidCallback())
                    ->isEqualTo([__CLASS__, 'strtoupper'])
                ->string($result->getHash())
                    ->isEqualTo('class#' . __CLASS__ . '::strtoupper')
                    ->isEqualTo($result . '')
                ->object($reflection = $result->getReflection())
                    ->isInstanceOf('ReflectionMethod')
                ->string($reflection->getName())
                    ->isEqualTo('strtoupper');
    }

    public function case_form_array_of_object_method()
    {
        $this
            ->when($result = new SUT([$this, 'strtolower']))
            ->then
                ->string($result('FOO'))
                    ->isEqualTo('foo')
                ->array($result->getValidCallback())
                    ->isEqualTo([$this, 'strtolower'])
                ->string($result->getHash())
                    ->matches(
                        '/^object\([^:]+\)#' .
                        preg_quote(__CLASS__) .
                        '::strtolower$/'
                    )
                    ->isEqualTo($result . '')
                ->object($reflection = $result->getReflection())
                    ->isInstanceOf('ReflectionMethod')
                ->string($reflection->getName())
                    ->isEqualTo('strtolower');
    }

    public function case_form_able_not_a_string()
    {
        $this
            ->exception(function () {
                new SUT(__CLASS__, 123);
            })
                ->isInstanceOf('Hoa\Consistency\Exception');
    }

    public function case_form_function_not_defined()
    {
        $this
            ->exception(function () {
                new SUT('__hoa_test_undefined_function__');
            })
                ->isInstanceOf('Hoa\Consistency\Exception');
    }

    public function case_form_able_cannot_be_deduced()
    {
        $this
            ->given($this->function->method_exists = false)
            ->exception(function () {
                new SUT($this);
            })
                ->isInstanceOf('Hoa\Consistency\Exception');
    }

    public function case_invoke()
    {
        $this
            ->given(
                $callable = new SUT(
                    function ($x, $y, $z) {
                        return [$x, $y, $z];
                    }
                )
            )
            ->when($result = $callable(7, [4.2], 'foo'))
            ->then
                ->array($result)
                    ->isEqualTo([7, [4.2], 'foo']);
    }

    public function case_distribute_arguments()
    {
        $this
            ->given(
                $callable = new SUT(
                    function ($x, $y, $z) {
                        return [$x, $y, $z];
                    }
                )
            )
            ->when($result = $callable->distributeArguments([7, [4.2], 'foo']))
            ->then
                ->array($result)
                    ->isEqualTo([7, [4.2], 'foo']);
    }

    protected function _get_valid_callback_stream_xxx($argument, $method)
    {
        $this
            ->given(
                $stream    = new \Mock\Hoa\Stream\IStream\Out(),
                $arguments = [$argument],
                $xcallable = new SUT($stream)
            )
            ->when($result = $xcallable->getValidCallback($arguments))
            ->then
                ->array($result)
                    ->isEqualTo([$stream, $method]);
    }

    public function case_get_valid_callback_stream_character()
    {
        return $this->_get_valid_callback_stream_xxx('f', 'writeCharacter');
    }

    public function case_get_valid_callback_stream_string()
    {
        return $this->_get_valid_callback_stream_xxx('foo', 'writeString');
    }

    public function case_get_valid_callback_stream_boolean()
    {
        return $this->_get_valid_callback_stream_xxx(true, 'writeBoolean');
    }

    public function case_get_valid_callback_stream_integer()
    {
        return $this->_get_valid_callback_stream_xxx(7, 'writeInteger');
    }

    public function case_get_valid_callback_stream_array()
    {
        return $this->_get_valid_callback_stream_xxx([4, 2], 'writeArray');
    }

    public function case_get_valid_callback_stream_float()
    {
        return $this->_get_valid_callback_stream_xxx(4.2, 'writeFloat');
    }

    public function case_get_valid_callback_stream_other()
    {
        return $this->_get_valid_callback_stream_xxx($this, 'writeAll');
    }

    public static function strtoupper($string)
    {
        return strtoupper($string);
    }

    public function strtolower($string)
    {
        return strtolower($string);
    }

    public function __invoke($string)
    {
        return strtoupper($string);
    }

    public function __toString()
    {
        return 'hello';
    }
}
