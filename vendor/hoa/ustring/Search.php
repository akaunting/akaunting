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

namespace Hoa\Ustring;

/**
 * Class \Hoa\Ustring\Search.
 *
 * Some algorithms about search in strings.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Search
{
    /**
     * Search by approximated patterns, with k differences based upon the
     * principle diagonal monotony.
     *
     * @param   string  $y    Haystack.
     * @param   string  $x    Needle.
     * @param   int     $k    Number of differences.
     * @return  array
     */
    public static function approximated($y, $x, $k)
    {
        $x      = (string) $x;
        $y      = (string) $y;
        $m      = strlen($x);
        $n      = strlen($y);
        $offset = [];
        $L      = [-1 => array_fill(-1, $n - $m + $k + 3, -2)];

        for ($q = 0, $max = $k - 1; $q <= $max; ++$q) {
            $L[$q][-$q - 1] = $L[$q][-$q - 2] = $q - 1;
        }

        for ($q = 0; $q <= $k; ++$q) {
            for ($d = -$q, $max = $n - $m + $k - $q; $d <= $max; ++$d) {
                $l         = min(
                                 max(
                                     $L[$q - 1][$d - 1],
                                     $L[$q - 1][$d    ] + 1,
                                     $L[$q - 1][$d + 1] + 1
                                 ),
                                 $m - 1
                             );
                $a         = substr($x, $l + 1, $m - $l);
                $b         = substr($y, $l + 1 + $d, $n - $l - $d);
                $L[$q][$d] = $l + static::lcp($a, $b);

                if ($L[$q][$d] == $m - 1 ||
                    $d + $L[$q][$d] == $n - 1) {
                    $j            = $m + $d;
                    $i            = max(0, $j - $m);
                    $offset[$q][] = ['i' => $i, 'j' => $j, 'l' => $j - $i];
                }
            }
        }

        return empty($offset) ? $offset : $offset[$k];
    }

    /**
     * Length of the longest common prefixes.
     *
     * @param   string  $x    Word.
     * @param   string  $y    Word.
     * @return  int
     */
    public static function lcp($x, $y)
    {
        $max = min(strlen($x), strlen($y));
        $i   = 0;

        while ($i < $max && $x[$i] == $y[$i]) {
            ++$i;
        }

        return $i;
    }
}
