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

namespace Hoa\Math\Test\Unit\Combinatorics\Combination;

use Hoa\Math\Combinatorics\Combination as CUT;
use Hoa\Test;

/**
 * Class \Hoa\Math\Test\Unit\Combinatorics\Combination.
 *
 * Test suite of the Γ combination.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Combination extends Test\Unit\Suite
{
    public function case_empty()
    {
        $this
            ->given(
                $n           = 0,
                $k           = 0,
                $withoutZero = false
            )
            ->when($result = CUT::Γ($n, $k, $withoutZero))
            ->then
                ->array($result)
                    ->isEmpty();
    }

    public function case_n2_k3()
    {
        $this
            ->given(
                $n           = 2,
                $k           = 3,
                $withoutZero = false
            )
            ->when($result = CUT::Γ($n, $k, $withoutZero))
            ->then
                ->array($result)
                    ->isEqualTo([
                        [3, 0],
                        [2, 1],
                        [1, 2],
                        [0, 3]
                    ]);
    }

    public function case_n3_k2()
    {
        $this
            ->given(
                $n           = 3,
                $k           = 2,
                $withoutZero = false
            )
            ->when($result = CUT::Γ($n, $k, $withoutZero))
            ->then
                ->array($result)
                    ->isEqualTo([
                        [2, 0, 0],
                        [1, 1, 0],
                        [1, 0, 1],
                        [0, 2, 0],
                        [0, 1, 1],
                        [0, 0, 2]
                    ]);
    }

    public function case_n2_k3_without_zero()
    {
        $this
            ->given(
                $n           = 2,
                $k           = 3,
                $withoutZero = true
            )
            ->when($result = CUT::Γ($n, $k, $withoutZero))
            ->then
                ->array($result)
                    ->isEqualTo([
                        [2, 1],
                        [1, 2]
                    ]);
    }

    public function case_n3_k2_without_zero()
    {
        $this
            ->given(
                $n           = 3,
                $k           = 2,
                $withoutZero = true
            )
            ->when($result = CUT::Γ($n, $k, $withoutZero))
            ->then
                ->array($result)
                    ->isEmpty();
    }
}
