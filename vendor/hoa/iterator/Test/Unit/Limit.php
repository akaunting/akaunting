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
 * Class \Hoa\Iterator\Test\Unit\Limit.
 *
 * Test suite of the limit iterator.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Limit extends Test\Unit\Suite
{
    private static $_dummyArray = ['f', 'o', 'o', 'b', 'a', 'r'];



    public function case_classic()
    {
        $this
            ->given(
                $iterator = new LUT\Map(self::$_dummyArray),
                $limit    = new LUT\Limit($iterator, 2, 3)
            )
            ->when($result = iterator_to_array($limit))
            ->then
                ->array($result)
                    ->isEqualTo([
                        2 => 'o',
                        3 => 'b',
                        4 => 'a'
                    ]);
    }

    public function case_negative_offset()
    {
        $this
            ->given($iterator = new LUT\Map(self::$_dummyArray))
            ->exception(function () use ($iterator) {
                new LUT\Limit($iterator, -2, 3);
            })
                ->isInstanceOf(\OutOfRangeException::class);
    }

    public function case_empty()
    {
        $this
            ->given(
                $iterator = new LUT\Map(self::$_dummyArray),
                $limit    = new LUT\Limit($iterator, 0, 0)
            )
            ->exception(function () use ($limit) {
                iterator_to_array($limit);
            })
                ->isInstanceOf(\OutOfBoundsException::class);
    }
}
