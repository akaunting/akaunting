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

namespace Hoa\Compiler\Test\Unit\Llk\Rule;

use Hoa\Test;
use Mock\Hoa\Compiler\Llk\Rule\Invocation as SUT;

/**
 * Class \Hoa\Compiler\Test\Unit\Llk\Rule\Invocation.
 *
 * Test suite of an invocation rule.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Invocation extends Test\Unit\Suite
{
    public function case_constructor()
    {
        $this
            ->given(
                $rule = 'foo',
                $data = 'bar'
            )
            ->when($result = new SUT($rule, $data))
            ->then
                ->string($result->getRule())
                    ->isEqualTo($rule)
                ->string($result->getData())
                    ->isEqualTo($data)
                ->variable($result->getTodo())
                    ->isNull()
                ->integer($result->getDepth())
                    ->isEqualTo(-1)
                ->boolean($result->isTransitional())
                    ->isFalse();
    }

    public function case_constructor_with_todo()
    {
        $this
            ->given(
                $rule = 'foo',
                $data = 'bar',
                $todo = ['baz', 'qux']
            )
            ->when($result = new SUT($rule, $data, $todo))
            ->then
                ->string($result->getRule())
                    ->isEqualTo($rule)
                ->string($result->getData())
                    ->isEqualTo($data)
                ->array($result->getTodo())
                    ->isEqualTo($todo)
                ->integer($result->getDepth())
                    ->isEqualTo(-1)
                ->boolean($result->isTransitional())
                    ->isFalse();
    }

    public function case_constructor_with_todo_and_depth()
    {
        $this
            ->given(
                $rule  = 'foo',
                $data  = 'bar',
                $todo  = ['baz', 'qux'],
                $depth = 42
            )
            ->when($result = new SUT($rule, $data, $todo, $depth))
            ->then
                ->string($result->getRule())
                    ->isEqualTo($rule)
                ->string($result->getData())
                    ->isEqualTo($data)
                ->array($result->getTodo())
                    ->isEqualTo($todo)
                ->integer($result->getDepth())
                    ->isEqualTo($depth)
                ->boolean($result->isTransitional())
                    ->isFalse();
    }

    public function case_set_depth()
    {
        $this
            ->given(
                $rule       = 42,
                $data       = 'bar',
                $todo       = ['baz', 'qux'],
                $depth      = 42,
                $invocation = new SUT($rule, $data)
            )
            ->when($result = $invocation->setDepth($depth))
            ->then
                ->integer($result)
                    ->isEqualTo(-1);
    }

    public function case_get_depth()
    {
        $this
            ->given(
                $rule       = 42,
                $data       = 'bar',
                $todo       = ['baz', 'qux'],
                $depth      = 42,
                $invocation = new SUT($rule, $data),
                $invocation->setDepth($depth)
            )
            ->when($result = $invocation->getDepth())
            ->then
                ->integer($result)
                    ->isEqualTo($depth);
    }

    public function case_is_transitional()
    {
        $this
            ->given(
                $rule       = 42,
                $data       = 'bar',
                $invocation = new SUT($rule, $data)
            )
            ->when($result = $invocation->isTransitional())
            ->then
                ->boolean($result)
                    ->isTrue();
    }
}
