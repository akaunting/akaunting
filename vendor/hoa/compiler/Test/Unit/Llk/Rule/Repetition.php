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

use Hoa\Compiler as LUT;
use Hoa\Compiler\Llk\Rule\Repetition as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Compiler\Test\Unit\Llk\Rule\Repetition.
 *
 * Test suite of a repetition rule.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Repetition extends Test\Unit\Suite
{
    public function case_is_a_rule()
    {
        $this
            ->when($result = new SUT('foo', 7, 42, [], 'bar'))
            ->then
                ->object($result)
                    ->isInstanceOf(LUT\Llk\Rule::class);
    }

    public function case_constructor()
    {
        $this
            ->given(
                $name     = 'foo',
                $min      = 7,
                $max      = 42,
                $children = [],
                $id       = 'bar'
            )
            ->when($result = new SUT($name, $min, $max, $children, $id))
            ->then
                ->string($result->getName())
                    ->isEqualTo($name)
                ->integer($result->getMin())
                    ->isEqualTo($min)
                ->integer($result->getMax())
                    ->isEqualTo($max)
                ->array($result->getChildren())
                    ->isEqualTo($children)
                ->string($result->getNodeId())
                    ->isEqualTo($id)
                ->boolean($result->isInfinite())
                    ->isFalse();
    }

    public function case_constructor_min_and_max_are_casted_and_bounded()
    {
        $this
            ->given(
                $name     = 'foo',
                $min      = '-7',
                $max      = '42',
                $children = [],
                $id       = 'bar'
            )
            ->when($result = new SUT($name, $min, $max, $children, $id))
            ->then
                ->integer($result->getMin())
                    ->isEqualTo(0)
                ->integer($result->getMax())
                    ->isEqualTo(42)
                ->boolean($result->isInfinite())
                    ->isFalse();
    }

    public function case_constructor_min_is_greater_than_max()
    {
        $this
            ->given(
                $name     = 'foo',
                $min      = 2,
                $max      = 1,
                $children = [],
                $id       = 'bar'
            )
            ->exception(function () use ($name, $min, $max, $children, $id) {
                new SUT($name, $min, $max, $children, $id);
            })
                ->isInstanceOf(LUT\Exception\Rule::class)
                ->hasMessage('Cannot repeat with a min (2) greater than max (1).');
    }

    public function case_constructor_infinite_max()
    {
        $this
            ->given(
                $name     = 'foo',
                $min      = 2,
                $max      = -1,
                $children = [],
                $id       = 'bar'
            )
            ->when($result = new SUT($name, $min, $max, $children, $id))
            ->then
                ->integer($result->getMin())
                    ->isEqualTo(2)
                ->integer($result->getMax())
                    ->isEqualTo(-1)
                ->boolean($result->isInfinite())
                    ->isTrue();
    }

    public function case_get_min()
    {
        return $this->case_constructor();
    }

    public function case_get_max()
    {
        return $this->case_constructor();
    }
}
