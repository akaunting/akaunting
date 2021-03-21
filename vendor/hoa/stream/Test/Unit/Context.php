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

namespace Hoa\Stream\Test\Unit;

use Hoa\Stream as LUT;
use Hoa\Stream\Context as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Stream\Test\Unit\Context.
 *
 * Test suite of the context stream class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Context extends Test\Unit\Suite
{
    public function case_get_instance_with_empty_id()
    {
        $this
            ->exception(function () {
                SUT::getInstance(null);
            })
            ->isInstanceOf(LUT\Exception::class);
    }

    public function case_get_new_instance()
    {
        $this
            ->when($result = SUT::getInstance('foo'))
            ->then
                ->object($result)
                    ->isInstanceOf(SUT::class);
    }

    public function case_get_new_instances()
    {
        $this
            ->when($result = SUT::getInstance('foo'))
            ->then
                ->object($result)
                    ->isNotIdenticalTo(SUT::getInstance('bar'));
    }

    public function case_get_same_instance()
    {
        $this
            ->when($result = SUT::getInstance('foo'))
            ->then
                ->object($result)
                    ->isIdenticalTo(SUT::getInstance('foo'));
    }

    public function case_get_id()
    {
        $this
            ->given(
                $id      = 'foo',
                $context = SUT::getInstance($id)
            )
            ->when($result = $context->getId())
            ->then
                ->string($result)
                    ->isEqualTo($id);
    }

    public function case_context_exists()
    {
        $this
            ->given(
                $id = 'foo',
                SUT::getInstance($id)
            )
            ->when($result = SUT::contextExists($id))
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_context_does_not_exist()
    {
        $this
            ->when($result = SUT::contextExists('foo'))
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_set_options()
    {
        $this
            ->given(
                $context = SUT::getInstance('foo'),
                $options = ['bar' => ['baz' => 'qux']]
            )
            ->when($result = $context->setOptions($options))
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_get_options()
    {
        $this
            ->given(
                $context = SUT::getInstance('foo'),
                $options = ['bar' => ['baz' => 'qux']],
                $context->setOptions($options)
            )
            ->when($result = $context->getOptions())
            ->then
                ->array($result)
                    ->isEqualTo($options);
    }

    public function case_set_parameters()
    {
        $this
            ->given(
                $context    = SUT::getInstance('foo'),
                $parameters = [
                    'notificaion' => 'callback',
                    'options'     => ['bar' => ['baz' => 'qux']]
                ]
            )
            ->when($result = $context->setParameters($parameters))
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_get_parameters()
    {
        $this
            ->given(
                $context    = SUT::getInstance('foo'),
                $parameters = [
                    'notification' => 'callback',
                    'options'      => ['bar' => ['baz' => 'qux']]
                ],
                $context->setParameters($parameters)
            )
            ->when($result = $context->getParameters())
            ->then
                ->array($result)
                    ->isEqualTo($parameters);
    }

    public function case_get_context()
    {
        $this
            ->given($context = SUT::getInstance('foo'))
            ->when($result = $context->getContext())
            ->then
                ->resource($result)
                    ->isStreamContext();
    }
}
