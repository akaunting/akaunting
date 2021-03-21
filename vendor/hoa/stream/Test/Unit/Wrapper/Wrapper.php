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

namespace Hoa\Stream\Test\Unit\Wrapper;

use Hoa\Stream as LUT;
use Hoa\Stream\Wrapper\Wrapper as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Stream\Test\Unit\Wrapper\Wrapper.
 *
 * Test suite of the wrapper class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Wrapper extends Test\Unit\Suite
{
    public function case_register()
    {
        $this
            ->given($oldIsRegistered = SUT::isRegistered('foo'))
            ->when($result = SUT::register('foo', 'StdClass'))
            ->then
                ->boolean($result)
                    ->isTrue()
                ->boolean($oldIsRegistered)
                    ->isFalse()
                ->boolean(SUT::isRegistered('foo'))
                    ->isTrue();
    }

    public function case_register_already_registered()
    {
        $this
            ->exception(function () {
                SUT::register('php', 'ClassName');
            })
                ->isInstanceOf(LUT\Exception::class)
                ->hasMessage('The protocol php is already registered.');
    }

    public function case_register_implementation_does_not_exist()
    {
        $this
            ->exception(function () {
                SUT::register('foo', 'ClassName');
            })
                ->isInstanceOf(LUT\Exception::class)
                ->hasMessage(
                    'Cannot use the ClassName class for the implementation ' .
                    'of the foo protocol because it is not found.'
                );
    }

    public function case_unregister()
    {
        $this
            ->given(
                SUT::register('foo', 'StdClass'),
                $oldIsRegistered = SUT::isRegistered('foo')
            )
            ->when($result = SUT::unregister('foo'))
            ->then
                ->boolean($result)
                    ->isTrue()
                ->boolean($oldIsRegistered)
                    ->isTrue()
                ->boolean(SUT::isRegistered('foo'))
                    ->isFalse();
    }

    public function case_unregister_unregistered_protocol()
    {
        $this
            ->when($result = SUT::unregister('foo'))
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_restore_registered_protocol()
    {
        $this
            ->when($result = SUT::restore('php'))
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_restore_unregistered_protocol()
    {
        $this
            ->when($result = SUT::restore('foo'))
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_is_registered()
    {
        $this
            ->when($result = SUT::isRegistered('php'))
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_is_not_registered()
    {
        $this
            ->when($result = SUT::isRegistered('foo'))
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_get_registered()
    {
        $this
            ->when($result = SUT::getRegistered())
            ->then
                ->array($result)
                    ->containsValues([
                        'https',
                        'php',
                        'file',
                        'glob',
                        'data',
                        'http',
                        'hoa'
                    ]);
    }

    public function case_get_registered_dynamically()
    {
        $this
            ->given($oldCount = count(SUT::getRegistered()))
            ->when(
                SUT::register('foo', \StdClass::class),
                $result = SUT::getRegistered()
            )
            ->then
                ->integer(count($result))
                    ->isEqualTo($oldCount + 1)

            ->when(
                SUT::unregister('foo'),
                $result = SUT::getRegistered()
            )
            ->then
                ->integer(count($result))
                    ->isEqualTo($oldCount);
    }
}
