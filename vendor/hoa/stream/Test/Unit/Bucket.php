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

namespace Hoa\Stream\Test\Unit;

use Hoa\Stream\Bucket as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Stream\Test\Unit\Bucket.
 *
 * Test suite of the stream bucket.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Bucket extends Test\Unit\Suite
{
    public function case_constants()
    {
        $this
            ->boolean(SUT::IS_A_BRIGADE)
                ->isTrue()
            ->boolean(SUT::IS_A_STREAM)
                ->isFalse();
    }

    public function case_construct_a_brigade()
    {
        $this
            ->given($brigade = 'foo')
            ->when($result = new SUT($brigade, SUT::IS_A_BRIGADE))
            ->then
                ->boolean($result->getType())
                    ->isEqualTo(SUT::IS_A_BRIGADE)
                ->variable($result->getBrigade())
                    ->isIdenticalTo($brigade)
                    ->isIdenticalTo('foo');
    }

    public function case_construct_a_stream()
    {
        $this
            ->given(
                $stream = fopen(__FILE__, 'r'),
                $buffer = 'bar'
            )
            ->when($result = new SUT($stream, SUT::IS_A_STREAM, $buffer))
            ->then
                ->boolean($result->getType())
                    ->isEqualTo(SUT::IS_A_STREAM)
                ->let($bucket = $this->invoke($result)->getBucket())
                ->object($bucket)
                    ->isInstanceOf(\StdClass::class)
                ->resource($bucket->bucket)
                ->string($bucket->data)
                    ->isEqualTo($buffer)
                ->integer($bucket->datalen)
                    ->isEqualTo(strlen($buffer))
                ->object($result->getBrigade())
                    ->isIdenticalTo($bucket);
    }

    public function case_eob()
    {
        $this
            ->given(
                $stream = fopen(__FILE__, 'r'),
                $bucket = new SUT($stream, SUT::IS_A_STREAM)
            )
            ->when($result = $bucket->eob())
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_set_data()
    {
        $this
            ->given(
                $stream    = fopen(__FILE__, 'r'),
                $oldBuffer = 'bar',
                $bucket    = new SUT($stream, SUT::IS_A_STREAM, $oldBuffer),
                $buffer    = 'bazqux'
            )
            ->when($result = $bucket->setData('bazqux'))
            ->then
                ->string($result)
                    ->isEqualTo($oldBuffer)
                ->let($_bucket = $this->invoke($bucket)->getBucket())
                ->object($_bucket)
                    ->isInstanceOf(\StdClass::class)
                ->resource($_bucket->bucket)
                ->string($_bucket->data)
                    ->isEqualTo($buffer)
                ->integer($_bucket->datalen)
                    ->isEqualTo(strlen($buffer))
                ->object($bucket->getBrigade())
                    ->isIdenticalTo($_bucket);
    }

    public function case_get_data()
    {
        $this
            ->given(
                $stream = fopen(__FILE__, 'r'),
                $buffer = 'bar',
                $bucket = new SUT($stream, SUT::IS_A_STREAM, $buffer)
            )
            ->when($result = $bucket->getData())
            ->then
                ->string($result)
                    ->isEqualTo($buffer)
                    ->isEqualTo($this->invoke($bucket)->getBucket()->data);
    }

    public function case_get_length()
    {
        $this
            ->given(
                $stream = fopen(__FILE__, 'r'),
                $buffer = 'bar',
                $bucket = new SUT($stream, SUT::IS_A_STREAM, $buffer)
            )
            ->when($result = $bucket->getLength())
            ->then
                ->integer($result)
                    ->isEqualTo(strlen($buffer))
                    ->isEqualTo($this->invoke($bucket)->getBucket()->datalen);
    }
}
