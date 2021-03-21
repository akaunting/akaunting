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
 * Class \Hoa\Iterator\Test\Unit\Repeater.
 *
 * Test suite of the repeater iterator.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Repeater extends Test\Unit\Suite
{
    private static $_dummyArray = ['f', 'o', 'o', 'b', 'a', 'r'];



    public function case_classic()
    {
        $this
            ->given(
                $iterator = new LUT\Map(self::$_dummyArray),
                $repeater = new LUT\Repeater($iterator, 3)
            )
            ->when($result = iterator_to_array($repeater))
            ->then
                ->array($result)
                    ->isEqualTo(
                        self::$_dummyArray +
                        self::$_dummyArray +
                        self::$_dummyArray
                    );
    }

    public function case_with_body()
    {
        $self = $this;

        $this
            ->given(
                $iterator = new LUT\Map(self::$_dummyArray),
                $count    = 0,
                $repeater = new LUT\Repeater(
                    $iterator,
                    3,
                    function ($repetition) use ($self, &$count) {
                        $this
                            ->integer($repetition)
                                ->isEqualTo($count + 1);

                        ++$count;
                    })
            )
            ->when($result = iterator_to_array($repeater))
            ->then
                ->array($result)
                    ->isEqualTo(
                        self::$_dummyArray +
                        self::$_dummyArray +
                        self::$_dummyArray
                    )
                ->integer($count)
                    ->isEqualTo(3);
    }
}
