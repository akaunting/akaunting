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

namespace Hoa\Math\Combinatorics\Combination;

use Hoa\Consistency;

/**
 * Class \Hoa\Math\Combinatorics\Combination.
 *
 * Some functions related to combinatorics.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Combination
{
    /**
     * Γ^n_k denotes the set of k-uples whose sum of elements is n. For example:
     * Γ^3_2 = {(2, 0, 0), (1, 1, 0), (1, 0, 1), (0, 2, 0), (0, 1, 1), (0, 0,
     * 2)}. For any k-uple γ and any α in {1, …, k}, γ_α denotes the α-th
     * element of γ.
     *
     * @param   int   $n              n.
     * @param   int   $k              k.
     * @param   bool  $withoutZero    Do not produce solutions with a zero
     *                                inside.
     * @return  array
     */
    public static function Γ($n, $k, $withoutZero = false)
    {
        if (0 === $n) {
            return [];
        }

        $out  = [];
        $tmp  = null;
        $i    = 0;
        $o    = array_fill(0, $n, 0);
        $o[0] = $k;

        while ($k != $o[$i = $n - 1]) {
            if (false === $withoutZero || !in_array(0, $o)) {
                $out[] = $o;
            }

            $tmp   = $o[$i];
            $o[$i] = 0;

            while ($o[$i] == 0) {
                --$i;
            }

            --$o[$i];
            $o[$i + 1] = $tmp + 1;
        }

        if (false === $withoutZero || !in_array(0, $o)) {
            $out[] = $o;
        }

        return $out;
    }
}

/**
 * Flex entity.
 */
Consistency::flexEntity('Hoa\Math\Combinatorics\Combination\Combination');
