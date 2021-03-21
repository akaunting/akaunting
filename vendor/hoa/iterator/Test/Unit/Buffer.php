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
use Hoa\Iterator\Buffer as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Iterator\Test\Unit\Buffer.
 *
 * Test suite of the buffer iterator.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Buffer extends Test\Unit\Suite
{
    public function case_constructor()
    {
        $this
            ->given(
                $innerIterator = $this->getInnerIterator(),
                $bufferSize    = 3
            )
            ->when($result = new SUT($innerIterator, $bufferSize))
            ->then
                ->object($result->getInnerIterator())
                    ->isIdenticalTo($innerIterator)
                ->integer($result->getBufferSize())
                    ->isEqualTo($bufferSize)
                ->let($buffer = $this->invoke($result)->getBuffer())
                ->object($buffer)
                    ->isInstanceOf(\SplDoublyLinkedList::class)
                ->boolean($buffer->isEmpty())
                    ->isTrue();
    }

    public function case_negative_buffer_size()
    {
        $this
            ->given(
                $innerIterator = $this->getInnerIterator(),
                $bufferSize    = -42
            )
            ->when($result = new SUT($innerIterator, $bufferSize))
            ->then
                ->integer($result->getBufferSize())
                    ->isEqualTo(1);
    }

    public function case_null_buffer_size()
    {
        $this
            ->given(
                $innerIterator = $this->getInnerIterator(),
                $bufferSize    = 0
            )
            ->when($result = new SUT($innerIterator, $bufferSize))
            ->then
                ->integer($result->getBufferSize())
                    ->isEqualTo(1);
    }

    public function case_fast_forward()
    {
        $this
            ->given($iterator = new SUT($this->getInnerIterator(), 3))
            ->when($result = iterator_to_array($iterator))
            ->then
                ->array($result)
                    ->isEqualTo(['a', 'b', 'c', 'd', 'e'])
                ->array(iterator_to_array($this->invoke($iterator)->getBuffer()))
                    ->isEqualTo([
                        0 => [
                            $iterator::BUFFER_KEY   => 3,
                            $iterator::BUFFER_VALUE => 'd'
                        ],
                        1 => [
                            $iterator::BUFFER_KEY   => 4,
                            $iterator::BUFFER_VALUE => 'e'
                        ],
                        2 => [
                            $iterator::BUFFER_KEY   => null,
                            $iterator::BUFFER_VALUE => null
                        ]
                    ]);
    }

    public function case_fast_forward_with_too_big_buffer()
    {
        $this
            ->given($iterator = new SUT($this->getInnerIterator(), 10))
            ->when($result = iterator_to_array($iterator))
            ->then
                ->array($result)
                    ->isEqualTo(['a', 'b', 'c', 'd', 'e'])
                ->array(iterator_to_array($this->invoke($iterator)->getBuffer()))
                    ->isEqualTo([
                        0 => [
                            $iterator::BUFFER_KEY   => 0,
                            $iterator::BUFFER_VALUE => 'a'
                        ],
                        1 => [
                            $iterator::BUFFER_KEY   => 1,
                            $iterator::BUFFER_VALUE => 'b'
                        ],
                        2 => [
                            $iterator::BUFFER_KEY   => 2,
                            $iterator::BUFFER_VALUE => 'c'
                        ],
                        3 => [
                            $iterator::BUFFER_KEY   => 3,
                            $iterator::BUFFER_VALUE => 'd'
                        ],
                        4 => [
                            $iterator::BUFFER_KEY   => 4,
                            $iterator::BUFFER_VALUE => 'e'
                        ],
                        5 => [
                            $iterator::BUFFER_KEY   => null,
                            $iterator::BUFFER_VALUE => null
                        ]
                    ]);
    }

    public function case_fast_forward_with_smallest_buffer()
    {
        $this
            ->given($iterator = new SUT($this->getInnerIterator(), 1))
            ->when($result = iterator_to_array($iterator))
            ->then
                ->array($result)
                    ->isEqualTo(['a', 'b', 'c', 'd', 'e'])
                ->array(iterator_to_array($this->invoke($iterator)->getBuffer()))
                    ->isEqualTo([
                        0 => [
                            $iterator::BUFFER_KEY   => null,
                            $iterator::BUFFER_VALUE => null
                        ]
                    ]);
    }

    public function case_forward_forward_forward()
    {
        $this
            ->when($result = new SUT(new LUT\Map(['a', 'b', 'c']), 2))
            ->then
                ->variable($result->rewind())
                    ->isNull()
                ->boolean($result->valid())
                    ->isTrue()
                ->integer($result->key())
                    ->isEqualTo(0)
                ->string($result->current())
                    ->isEqualTo('a')
                ->variable($result->next())
                    ->isNull()

                ->boolean($result->valid())
                    ->isTrue()
                ->integer($result->key())
                    ->isEqualTo(1)
                ->string($result->current())
                    ->isEqualTo('b')
                ->variable($result->next())
                    ->isNull()

                ->boolean($result->valid())
                    ->isTrue()
                ->integer($result->key())
                    ->isEqualTo(2)
                ->string($result->current())
                    ->isEqualTo('c')
                ->variable($result->next())
                    ->isNull()

                ->boolean($result->valid())
                    ->isFalse()
                ->variable($result->key())
                    ->isNull()
                ->variable($result->current())
                    ->isNull();
    }

    public function case_forward_forward_backward_backward_forward_forward_forward_step_by_step()
    {
        $this
            ->when($result = new SUT(new LUT\Map(['a', 'b', 'c']), 3))
            ->then
                ->variable($result->rewind())
                    ->isNull()
                ->array(iterator_to_array($this->invoke($result)->getBuffer()))
                    ->isEqualTo([
                        0 => [
                            $result::BUFFER_KEY   => 0,
                            $result::BUFFER_VALUE => 'a'
                        ]
                    ])

                ->boolean($result->valid())
                    ->isTrue()
                ->integer($result->key())
                    ->isEqualTo(0)
                ->string($result->current())
                    ->isEqualTo('a')
                ->variable($result->next())
                    ->isNull()
                ->array(iterator_to_array($this->invoke($result)->getBuffer()))
                    ->isEqualTo([
                        0 => [
                            $result::BUFFER_KEY   => 0,
                            $result::BUFFER_VALUE => 'a'
                        ],
                        1 => [
                            $result::BUFFER_KEY   => 1,
                            $result::BUFFER_VALUE => 'b'
                        ]
                    ])

                ->boolean($result->valid())
                    ->isTrue()
                ->integer($result->key())
                    ->isEqualTo(1)
                ->string($result->current())
                    ->isEqualTo('b')
                ->variable($result->next())
                    ->isNull()
                ->array(iterator_to_array($this->invoke($result)->getBuffer()))
                    ->isEqualTo([
                        0 => [
                            $result::BUFFER_KEY   => 0,
                            $result::BUFFER_VALUE => 'a'
                        ],
                        1 => [
                            $result::BUFFER_KEY   => 1,
                            $result::BUFFER_VALUE => 'b'
                        ],
                        2 => [
                            $result::BUFFER_KEY   => 2,
                            $result::BUFFER_VALUE => 'c'
                        ]
                    ])

                ->boolean($result->valid())
                    ->isTrue()
                ->integer($result->key())
                    ->isEqualTo(2)
                ->string($result->current())
                    ->isEqualTo('c')
                ->variable($result->previous())
                    ->isNull()
                ->array(iterator_to_array($this->invoke($result)->getBuffer()))
                    ->isEqualTo([
                        0 => [
                            $result::BUFFER_KEY   => 0,
                            $result::BUFFER_VALUE => 'a'
                        ],
                        1 => [
                            $result::BUFFER_KEY   => 1,
                            $result::BUFFER_VALUE => 'b'
                        ],
                        2 => [
                            $result::BUFFER_KEY   => 2,
                            $result::BUFFER_VALUE => 'c'
                        ]
                    ])

                ->boolean($result->valid())
                    ->isTrue()
                ->integer($result->key())
                    ->isEqualTo(1)
                ->string($result->current())
                    ->isEqualTo('b')
                ->variable($result->previous())
                    ->isNull()
                ->array(iterator_to_array($this->invoke($result)->getBuffer()))
                    ->isEqualTo([
                        0 => [
                            $result::BUFFER_KEY   => 0,
                            $result::BUFFER_VALUE => 'a'
                        ],
                        1 => [
                            $result::BUFFER_KEY   => 1,
                            $result::BUFFER_VALUE => 'b'
                        ],
                        2 => [
                            $result::BUFFER_KEY   => 2,
                            $result::BUFFER_VALUE => 'c'
                        ]
                    ])

                ->boolean($result->valid())
                    ->isTrue()
                ->integer($result->key())
                    ->isEqualTo(0)
                ->string($result->current())
                    ->isEqualTo('a')
                ->variable($result->next())
                    ->isNull()
                ->array(iterator_to_array($this->invoke($result)->getBuffer()))
                    ->isEqualTo([
                        0 => [
                            $result::BUFFER_KEY   => 0,
                            $result::BUFFER_VALUE => 'a'
                        ],
                        1 => [
                            $result::BUFFER_KEY   => 1,
                            $result::BUFFER_VALUE => 'b'
                        ],
                        2 => [
                            $result::BUFFER_KEY   => 2,
                            $result::BUFFER_VALUE => 'c'
                        ]
                    ])

                ->boolean($result->valid())
                    ->isTrue()
                ->integer($result->key())
                    ->isEqualTo(1)
                ->string($result->current())
                    ->isEqualTo('b')
                ->variable($result->next())
                    ->isNull()
                ->array(iterator_to_array($this->invoke($result)->getBuffer()))
                    ->isEqualTo([
                        0 => [
                            $result::BUFFER_KEY   => 0,
                            $result::BUFFER_VALUE => 'a'
                        ],
                        1 => [
                            $result::BUFFER_KEY   => 1,
                            $result::BUFFER_VALUE => 'b'
                        ],
                        2 => [
                            $result::BUFFER_KEY   => 2,
                            $result::BUFFER_VALUE => 'c'
                        ]
                    ])

                ->boolean($result->valid())
                    ->isTrue()
                ->integer($result->key())
                    ->isEqualTo(2)
                ->string($result->current())
                    ->isEqualTo('c')
                ->variable($result->next())
                    ->isNull()
                ->array(iterator_to_array($this->invoke($result)->getBuffer()))
                    ->isEqualTo([
                        0 => [
                            $result::BUFFER_KEY   => 1,
                            $result::BUFFER_VALUE => 'b'
                        ],
                        1 => [
                            $result::BUFFER_KEY   => 2,
                            $result::BUFFER_VALUE => 'c'
                        ],
                        2 => [
                            $result::BUFFER_KEY   => null,
                            $result::BUFFER_VALUE => null
                        ]
                    ])

                ->boolean($result->valid())
                    ->isFalse()
                ->variable($result->key())
                    ->isNull()
                ->variable($result->current())
                    ->isNull();
    }

    public function case_backward_out_of_buffer()
    {
        $this
            ->when($result = new SUT(new LUT\Map(['a', 'b', 'c']), 1))
            ->then
                ->variable($result->rewind())
                    ->isNull()
                ->boolean($result->valid())
                    ->isTrue()
                ->integer($result->key())
                    ->isEqualTo(0)
                ->string($result->current())
                    ->isEqualTo('a')
                ->variable($result->next())
                    ->isNull()

                ->boolean($result->valid())
                    ->isTrue()
                ->integer($result->key())
                    ->isEqualTo(1)
                ->string($result->current())
                    ->isEqualTo('b')
                ->variable($result->previous())
                    ->isNull()

                ->boolean($result->valid())
                    ->isFalse();
    }

    public function case_rewind_rewind()
    {
        $this
            ->when($result = new SUT(new LUT\Map(['a', 'b']), 3))
            ->then
                ->variable($result->rewind())
                    ->isNull()
                ->boolean($result->valid())
                    ->isTrue()
                ->integer($result->key())
                    ->isEqualTo(0)
                ->string($result->current())
                    ->isEqualTo('a')
                ->variable($result->next())
                    ->isNull()

                ->variable($result->rewind())
                    ->isNull()
                ->boolean($result->valid())
                    ->isTrue()
                ->integer($result->key())
                    ->isEqualTo(0)
                ->string($result->current())
                    ->isEqualTo('a')
                ->variable($result->next())
                    ->isNull()

                ->array(iterator_to_array($this->invoke($result)->getBuffer()))
                    ->isEqualTo([
                        0 => [
                            $result::BUFFER_KEY   => 0,
                            $result::BUFFER_VALUE => 'a'
                        ],
                        1 => [
                            $result::BUFFER_KEY   => 1,
                            $result::BUFFER_VALUE => 'b'
                        ]
                    ]);
    }

    protected function getInnerIterator()
    {
        return new LUT\Map(['a', 'b', 'c', 'd', 'e']);
    }
}
