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

namespace Hoa\Event\Test\Unit;

use Hoa\Event as LUT;
use Hoa\Event\Bucket as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Event\Test\Unit\Bucket.
 *
 * Test suite of the bucket.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Bucket extends Test\Unit\Suite
{
    public function case_constructor()
    {
        $this
            ->when($result = new SUT('foo'))
            ->then
                ->object($result)
                    ->isInstanceOf('Hoa\Event\Bucket')
                ->string($result->getData())
                    ->isEqualTo('foo');
    }

    public function case_send()
    {
        $self = $this;

        $this
            ->given(
                $eventId = 'hoa://Event/Test',
                $source  = new \Mock\Hoa\Event\Source(),
                LUT::register($eventId, $source),

                $bucket = new SUT('foo'),

                LUT::getEvent($eventId)->attach(
                    function (SUT $receivedBucket) use ($self, $bucket, &$called) {
                        $called = true;

                        $self
                            ->object($receivedBucket)
                                ->isIdenticalTo($bucket);
                    }
                )
            )
            ->when($result = $bucket->send($eventId, $source))
            ->then
                ->variable($result)
                    ->isNull()
                ->boolean($called)
                    ->isTrue();
    }

    public function case_set_source()
    {
        $this
            ->given(
                $bucket  = new SUT(),
                $sourceA = new \Mock\Hoa\Event\Source()
            )
            ->when($result = $bucket->setSource($sourceA))
            ->then
                ->variable($result)
                    ->isNull()
                ->object($bucket->getSource())
                    ->isIdenticalTo($sourceA)

            ->given($sourceB = new \Mock\Hoa\Event\Source())
            ->when($result = $bucket->setSource($sourceB))
            ->then
                ->object($result)
                    ->isIdenticalTo($sourceA)
                ->object($bucket->getSource())
                    ->isIdenticalTo($sourceB);
    }

    public function case_set_data()
    {
        $this
            ->given(
                $bucket = new SUT(),
                $datumA = 'foo'
            )
            ->when($result = $bucket->setData($datumA))
            ->then
                ->variable($result)
                    ->isNull()
                ->string($bucket->getData())
                    ->isEqualTo($datumA)

            ->given($datumB = 'bar')
            ->when($result = $bucket->setData($datumB))
            ->then
                ->string($result)
                    ->isEqualTo($datumA)
                ->string($bucket->getData())
                    ->isEqualTo($datumB);
    }
}
