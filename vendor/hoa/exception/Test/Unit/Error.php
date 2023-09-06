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

namespace Hoa\Exception\Test\Unit;

use Hoa\Exception\Error as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Exception\Test\Unit\Error.
 *
 * Test suite of the error class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Error extends Test\Unit\Suite
{
    public function case_is_an_exception()
    {
        $this
            ->when($result = new SUT('foo', 42, '/hoa/flatland', 153))
            ->then
                ->object($result)
                    ->isInstanceOf('Hoa\Exception\Exception');
    }

    public function case_get_message()
    {
        $this
            ->given($exception = new SUT('foo', 42, '/hoa/flatland', 153))
            ->when($result = $exception->raise())
            ->then
                ->string($result)
                    ->isEqualTo(
                        '{main}: (42) foo' . "\n" .
                        'in /hoa/flatland at line 153.'
                    );
    }

    public function case_disable_error_handler()
    {
        $this
            ->given(
                $this->function->restore_error_handler = function () use (&$called) {
                    $called = true;

                    return null;
                }
            )
            ->when($result = SUT::enableErrorHandler(false))
            ->then
                ->variable($result)
                    ->isNull()
                ->boolean($called)
                    ->isTrue();
    }

    public function case_enable_error_handler()
    {
        $self = $this;

        $this
            ->given(
                $this->function->set_error_handler = function ($handler) use ($self, &$called) {
                    $called = true;

                    $self
                        ->object($handler)
                            ->isInstanceOf('Closure')
                        ->let($reflection = new \ReflectionObject($handler))
                        ->array($invokeParameters = $reflection->getMethod('__invoke')->getParameters())
                            ->hasSize(5)
                        ->string($invokeParameters[0]->getName())
                            ->isEqualTo('no')
                        ->string($invokeParameters[1]->getName())
                            ->isEqualTo('str')
                        ->string($invokeParameters[2]->getName())
                            ->isEqualTo('file')
                        ->boolean($invokeParameters[2]->isOptional())
                            ->isTrue()
                        ->string($invokeParameters[3]->getName())
                            ->isEqualTo('line')
                        ->boolean($invokeParameters[3]->isOptional())
                            ->isTrue()
                        ->string($invokeParameters[4]->getName())
                            ->isEqualTo('ctx')
                        ->boolean($invokeParameters[4]->isOptional())
                            ->isTrue();

                    return null;
                }
            )
            ->when($result = SUT::enableErrorHandler())
            ->then
                ->variable($result)
                    ->isNull()
                ->boolean($called)
                    ->isTrue();
    }

    public function case_error_handler()
    {
        $this
            ->given(SUT::enableErrorHandler())
            ->exception(function () {
                ++$i;
            })
                ->isInstanceOf('Hoa\Exception\Error')
                ->hasMessage('Undefined variable: i');
    }
}
