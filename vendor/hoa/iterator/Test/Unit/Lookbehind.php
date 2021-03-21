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

namespace Hoa\Iterator\Test\Unit;

use Hoa\Iterator as LUT;
use Hoa\Test;

/**
 * Class \Hoa\Iterator\Test\Unit\Lookbehind.
 *
 * Test suite of the look behind iterator.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Lookbehind extends Test\Unit\Suite
{
    public function case_traverse()
    {
        $this
            ->given(
                $iterator   = new LUT\Map(['a', 'b', 'c']),
                $lookbehind = new LUT\Lookbehind($iterator)
            )
            ->when($result = iterator_to_array($iterator))
            ->then
                ->array($result)
                    ->isEqualTo(['a', 'b', 'c']);
    }

    public function case_check_behind()
    {
        $this
            ->given(
                $iterator   = new LUT\Map(['a', 'b', 'c']),
                $lookbehind = new LUT\Lookbehind($iterator)
            )
            ->when(
                $lookbehind->rewind(),
                $key         = $lookbehind->key(),
                $current     = $lookbehind->current(),
                $hasPrevious = $lookbehind->hasPrevious(),
                $previous    = $lookbehind->getPrevious()
            )
            ->then
                ->integer($key)
                    ->isEqualTo(0)
                ->string($current)
                    ->isEqualTo('a')
                ->boolean($hasPrevious)
                    ->isFalse()
                ->variable($previous)
                    ->isNull()

            ->when(
                $lookbehind->next(),
                $key         = $lookbehind->key(),
                $current     = $lookbehind->current(),
                $hasPrevious = $lookbehind->hasPrevious(),
                $previous    = $lookbehind->getPrevious()
            )
            ->then
                ->integer($key)
                    ->isEqualTo(1)
                ->string($current)
                    ->isEqualTo('b')
                ->boolean($hasPrevious)
                    ->isTrue()
                ->string($previous)
                    ->isEqualTo('a')

            ->when(
                $lookbehind->next(),
                $key         = $lookbehind->key(),
                $current     = $lookbehind->current(),
                $hasPrevious = $lookbehind->hasPrevious(),
                $previous    = $lookbehind->getPrevious()
            )
            ->then
                ->integer($key)
                    ->isEqualTo(2)
                ->string($current)
                    ->isEqualTo('c')
                ->boolean($hasPrevious)
                    ->isTrue()
                ->string($previous)
                    ->isEqualTo('b');
    }

    public function case_double_rewind()
    {
        $this
            ->given(
                $iterator   = new LUT\Map(['a', 'b', 'c']),
                $lookbehind = new LUT\Lookbehind($iterator)
            )
            ->when(
                $lookbehind->rewind(),
                $key         = $lookbehind->key(),
                $current     = $lookbehind->current(),
                $hasPrevious = $lookbehind->hasPrevious()
            )
            ->then
                ->integer($key)
                    ->isEqualTo(0)
                ->string($current)
                    ->isEqualTo('a')
                ->boolean($hasPrevious)
                    ->isFalse()

            ->when(
                $lookbehind->rewind(),
                $key         = $lookbehind->key(),
                $current     = $lookbehind->current(),
                $hasPrevious = $lookbehind->hasPrevious()
            )
            ->then
                ->integer($key)
                    ->isEqualTo(0)
                ->string($current)
                    ->isEqualTo('a')
                ->boolean($hasPrevious)
                    ->isFalse();
    }

    public function case_empty()
    {
        $this
            ->given(
                $iterator   = new LUT\Mock(),
                $lookbehind = new LUT\Lookbehind($iterator)
            )
            ->when(
                $lookbehind->rewind(),
                $valid = $lookbehind->valid()
            )
            ->then
                ->boolean($valid)
                    ->isFalse();
    }
}
