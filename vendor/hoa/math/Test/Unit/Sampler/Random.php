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

namespace Hoa\Math\Test\Unit\Sampler;

use Hoa\Math\Sampler\Random as CUT;
use Hoa\Test;

/**
 * Class \Hoa\Math\Test\Unit\Sampler\Random.
 *
 * Test suite of the random sampler.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Random extends Test\Unit\Suite
{
    public function case_integer()
    {
        $this
            ->given($sampler = new CUT())
            ->when($x = $sampler->getInteger())
            ->then
                ->integer($x);
    }

    public function case_bounded_integer()
    {
        $this
            ->given($sampler = new CUT())
            ->when($x = $sampler->getInteger(-5, 5))
            ->then
                ->integer($x)
                    ->isGreaterThanOrEqualTo(-5)
                    ->isLessThanOrEqualTo(5)
            ->when($y = $sampler->getInteger(42, 42))
            ->then
                ->integer($y)
                    ->isIdenticalTo(42);
    }

    public function case_optional_bounds_integer()
    {
        $this
            ->given($sampler = new CUT([
                'integer.min' => 42,
                'integer.max' => 42
            ]))
            ->when($x = $sampler->getInteger())
            ->then
                ->integer($x)
                    ->isIdenticalTo(42);
    }

    public function case_excluded_integers()
    {
        $this
            ->given(
                $exclude = [],
                $sampler = new CUT()
            )
            ->when($x = $sampler->getInteger(0, 2, $exclude))
            ->then
                ->integer($x)
                    ->isGreaterThanOrEqualTo(0)
                    ->isLessThanOrEqualTo(2)

            ->given($exclude[] = 2)
            ->when($y = $sampler->getInteger(0, 2, $exclude))
            ->then
                ->integer($y)
                    ->isGreaterThanOrEqualTo(0)
                    ->isLessThanOrEqualTo(1)

            ->given($exclude[] = 0)
            ->when($z = $sampler->getInteger(0, 2, $exclude))
            ->then
                ->integer($z)
                    ->isIdenticalTo(1);
    }

    public function case_uniformity_integer()
    {
        $this
            ->given(
                $max     = $this->sample(
                    $this->realdom()->boundinteger(1 << 18, 1 << 20)
                ),
                $sum     = 0,
                $upper   = 1 << 10,
                $sampler = new CUT([
                    'integer.min' => -$upper,
                    'integer.max' => $upper
                ])
            )
            ->when(function () use ($max, &$sum, &$sampler) {
                for ($i = 0; $i  < $max; ++$i) {
                    $sum += $sampler->getInteger();
                }
            })
            ->then
                ->float($sum / $max)
                    ->isGreaterThanOrEqualTo(-1.5)
                    ->isLessThanOrEqualTo(1.5);
    }

    public function case_float()
    {
        $this
            ->given($sampler = new CUT())
            ->when($x = $sampler->getFloat())
            ->then
                ->float($x);
    }

    public function case_bounded_float()
    {
        $this
            ->given($sampler = new CUT())
            ->when($x = $sampler->getFloat(-5.5, 5.5))
            ->then
                ->float($x)
                    ->isGreaterThanOrEqualTo(-5.5)
                    ->isLessThanOrEqualTo(5.5)
            ->when($y = $sampler->getFloat(4.2, 4.2))
                ->float($y)
                    ->isIdenticalTo(4.2);
    }

    public function case_optional_bounds_float()
    {
        $this
            ->given($sampler = new CUT([
                'float.min' => 4.2,
                'float.max' => 4.2
            ]))
            ->when($x = $sampler->getFloat())
            ->then
                ->float($x)
                    ->isIdenticalTo(4.2);
    }

    public function case_uniformity_float()
    {
        $this
            ->given(
                $max     = $this->sample(
                    $this->realdom()->boundinteger(1 << 18, 1 << 20)
                ),
                $sum     = 0,
                $upper   = 1 << 10,
                $sampler = new CUT([
                    'float.min' => -$upper,
                    'float.max' => $upper
                ])
            )
            ->when(function () use ($max, &$sum, &$sampler) {
                for ($i = 0; $i  < $max; ++$i) {
                    $sum += $sampler->getFloat();
                }
            })
            ->then
                ->float($sum / $max)
                    ->isGreaterThanOrEqualTo(-1.5)
                    ->isLessThanOrEqualTo(1.5);
    }
}
