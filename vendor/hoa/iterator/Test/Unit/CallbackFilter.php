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
 * Class \Hoa\Iterator\Test\Unit\CallbackFilter.
 *
 * Test suite of the callback filter iterator.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class CallbackFilter extends Test\Unit\Suite
{
    public function case_classic()
    {
        $this
            ->given(
                $foobar = $this->getDummyIterator(),
                $filter = new LUT\CallbackFilter(
                    $foobar,
                    function ($value) {
                        return false === in_array($value, ['a', 'e', 'i', 'o', 'u']);
                    }
                )
            )
            ->when($result = iterator_to_array($filter))
            ->then
                ->array($result)
                    ->isEqualTo([
                        0 => 'f',
                        3 => 'b',
                        5 => 'r'
                    ]);
    }

    public function case_all_callback_parameters()
    {
        $self = $this;

        $this
            ->given(
                $foobar = $this->getDummyIterator(),
                $keys   = [],
                $values = [],
                $filter = new LUT\CallbackFilter(
                    $foobar,
                    function ($value, $key, $iterator) use (
                        $self,
                        $foobar,
                        &$keys,
                        &$values
                    ) {
                        $self
                            ->object($iterator)
                                ->isIdenticalTo($foobar);

                        $keys[]   = $key;
                        $values[] = $value;

                        return false === in_array($value, ['a', 'e', 'i', 'o', 'u']);
                    }
                )
            )
            ->when($result = iterator_to_array($filter))
            ->then
                ->array($result)
                    ->isEqualTo([
                        0 => 'f',
                        3 => 'b',
                        5 => 'r'
                    ])
                ->array(array_combine($keys, $values))
                    ->isEqualTo(iterator_to_array($foobar));
    }

    public function case_remove_all()
    {
        $this
            ->given(
                $foobar = $this->getDummyIterator(),
                $filter = new LUT\CallbackFilter(
                    $foobar,
                    function () {
                        return false;
                    }
                )
            )
            ->when($result = iterator_to_array($filter))
            ->then
                ->array($result)
                    ->isEmpty();
    }

    public function case_remove_none()
    {
        $this
            ->given(
                $foobar = $this->getDummyIterator(),
                $filter = new LUT\CallbackFilter(
                    $foobar,
                    function () {
                        return true;
                    }
                )
            )
            ->when(
                $foobarResult = iterator_to_array($foobar),
                $filterResult = iterator_to_array($filter)
            )
            ->then
                ->array($foobarResult)
                    ->isEqualTo($filterResult);
    }

    public function case_recursive()
    {
        $this
            ->given(
                $foobar = $this->getDummyRecursiveIterator(),
                $filter = new LUT\Recursive\CallbackFilter(
                    $foobar,
                    function ($value) {
                        return false === in_array($value, ['a', 'e', 'i', 'o', 'u']);
                    }
                ),
                $iterator = new LUT\Recursive\Iterator($filter)
            )
            ->when($result = iterator_to_array($iterator, false))
            ->then
                ->array($result)
                    ->isEqualTo([
                        0 => 'f',
                        1 => 'b',
                        2 => 'r'
                    ]);
    }

    public function case_recursive_remove_all()
    {
        $this
            ->given(
                $foobar = $this->getDummyRecursiveIterator(),
                $filter = new LUT\Recursive\CallbackFilter(
                    $foobar,
                    function () {
                        return false;
                    }
                ),
                $iterator = new LUT\Recursive\Iterator($filter)
            )
            ->when($result = iterator_to_array($iterator))
            ->then
                ->array($result)
                    ->isEmpty();
    }

    public function case_recursive_remove_none()
    {
        $this
            ->given(
                $foobar = $this->getDummyRecursiveIterator(),
                $filter = new LUT\Recursive\CallbackFilter(
                    $foobar,
                    function () {
                        return true;
                    }
                ),
                $foobarIterator = new LUT\Recursive\Iterator($foobar),
                $filterIterator = new LUT\Recursive\Iterator($filter)
            )
            ->when(
                $foobarResult = iterator_to_array($foobarIterator),
                $filterResult = iterator_to_array($filterIterator)
            )
            ->then
                ->array($foobarResult)
                    ->isEqualTo($filterResult);
    }

    protected function getDummyIterator()
    {
        return new LUT\Map(['f', 'o', 'o', 'b', 'a', 'r']);
    }

    protected function getDummyRecursiveIterator()
    {
        return new LUT\Recursive\Map([['f', 'o', 'o'], ['b', 'a', 'r']]);
    }
}
