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
 * Class \Hoa\Iterator\Test\Unit\Multiple.
 *
 * Test suite of the multiple iterator.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Multiple extends Test\Unit\Suite
{
    public function case_classic()
    {
        $this
            ->given(
                $foo      = new LUT\Map(['f', 'o', 'o']),
                $bar      = new LUT\Map(['b', 'a', 'r']),
                $multiple = new LUT\Multiple(
                    LUT\Multiple::MIT_NEED_ANY
                  | LUT\Multiple::MIT_KEYS_ASSOC
                ),
                $multiple->attachIterator($foo, 'one'),
                $multiple->attachIterator($bar, 'two')
            )
            ->when($result = iterator_to_array($multiple, false))
            ->then
                ->array($result)
                    ->isEqualTo([
                        ['one' => 'f', 'two' => 'b'],
                        ['one' => 'o', 'two' => 'a'],
                        ['one' => 'o', 'two' => 'r']
                    ]);
    }

    public function case_default_value()
    {
        $this
            ->given(
                $foobar   = new LUT\Map(['f', 'o', 'o', 'b', 'a', 'r']),
                $baz      = new LUT\Map(['b', 'a', 'z']),
                $multiple = new LUT\Multiple(
                    LUT\Multiple::MIT_NEED_ANY
                  | LUT\Multiple::MIT_KEYS_ASSOC
                ),
                $multiple->attachIterator($foobar, 'one', '!'),
                $multiple->attachIterator($baz,    'two', '?')
            )
            ->when($result = iterator_to_array($multiple, false))
            ->then
                ->array($result)
                    ->isEqualTo([
                        ['one' => 'f', 'two' => 'b'],
                        ['one' => 'o', 'two' => 'a'],
                        ['one' => 'o', 'two' => 'z'],
                        ['one' => 'b', 'two' => '?'],
                        ['one' => 'a', 'two' => '?'],
                        ['one' => 'r', 'two' => '?'],
                    ]);
    }

    public function case_empty()
    {
        $this
            ->given($multiple = new LUT\Multiple())
            ->when($result = iterator_to_array($multiple))
            ->then
                ->array($result)
                    ->isEmpty();
    }
}
