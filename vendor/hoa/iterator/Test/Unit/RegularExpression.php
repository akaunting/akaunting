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
 * Class \Hoa\Iterator\Test\Unit\RegularExpression.
 *
 * Test suite of the regular expression iterator.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class RegularExpression extends Test\Unit\Suite
{
    public function case_classic()
    {
        $this
            ->given(
                $map = new LUT\Map([
                    'abc',
                    'dea',
                    'fgh',
                    'iaj',
                    'klm'
                ]),
                $iterator = new LUT\RegularExpression($map, '/a/')
            )
            ->when($result = iterator_to_array($iterator))
            ->then
                ->array($result)
                    ->isEqualTo([
                        0 => 'abc',
                        1 => 'dea',
                        3 => 'iaj'
                    ]);
    }

    public function case_recursive()
    {
        $this
            ->given(
                $map = new LUT\Recursive\Map([
                    ['abc', 'dea', 'fgh'],
                    ['iaj', 'klm']
                ]),
                $regex    = new LUT\Recursive\RegularExpression($map, '/a/'),
                $iterator = new LUT\Recursive\Iterator($regex)
            )
            ->when($result = iterator_to_array($iterator, false))
            ->then
                ->array($result)
                    ->isEqualTo([
                        0 => 'abc',
                        1 => 'dea',
                        2 => 'iaj'
                    ]);
    }

    public function case_recursive_children_flags()
    {
        $this
            ->given(
                $map = new LUT\Recursive\Map([
                    ['abc', 'dea', 'fgh'],
                    ['iaj', 'klm']
                ]),
                $mode     = LUT\Recursive\RegularExpression::ALL_MATCHES,
                $flag     = LUT\Recursive\RegularExpression::USE_KEY,
                $pregFlag = LUT\Recursive\RegularExpression::ALL_MATCHES,
                $iterator = new LUT\Recursive\RegularExpression(
                    $map,
                    '/a/',
                    $mode,
                    $flag,
                    $pregFlag
                )
            )
            ->when($result = $iterator->getChildren())
            ->then
                ->object($result)
                    ->isInstanceOf(LUT\Recursive\RegularExpression::class)
                ->integer($result->getMode())
                    ->isEqualTo($mode)
                ->integer($result->getFlags())
                    ->isEqualTo($flag)
                ->integer($result->getPregFlags())
                    ->isEqualTo($pregFlag);
    }
}
