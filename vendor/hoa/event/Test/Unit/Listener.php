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
use Hoa\Event\Listener as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Event\Test\Unit\Listener.
 *
 * Test suite of the listener.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Listener extends Test\Unit\Suite
{
    public function case_constructor()
    {
        $this
            ->given(
                $source = new \Mock\Hoa\Event\Listenable(),
                $ids    = ['foo', 'bar', 'baz']
            )
            ->when($result = new SUT($source, $ids))
            ->then
                ->object($result)
                    ->isInstanceOf('Hoa\Event\Listener')
                ->boolean($result->listenerExists('foo'))
                    ->isTrue()
                ->boolean($result->listenerExists('bar'))
                    ->isTrue()
                ->boolean($result->listenerExists('baz'))
                    ->isTrue();
    }

    public function case_attach()
    {
        $this
            ->given(
                $source     = new \Mock\Hoa\Event\Listenable(),
                $listenerId = 'foo',
                $listener   = new SUT($source, ['foo', 'bar']),
                $callable   = function () {
                    return 42;
                }
            )
            ->when($result = $listener->attach($listenerId, $callable))
            ->then
                ->object($result)
                    ->isIdenticalTo($listener)
                ->array($listener->fire($listenerId, new LUT\Bucket()))
                    ->isEqualTo([42]);
    }

    public function case_attach_to_an_undefined_listener()
    {
        $this
            ->given(
                $source     = new \Mock\Hoa\Event\Listenable(),
                $listenerId = 'bar',
                $listener   = new SUT($source, ['foo', 'baz']),
                $callable   = function () { }
            )
            ->exception(function () use ($listener, $listenerId, $callable) {
                $listener->attach($listenerId, $callable);
            })
                ->isInstanceOf('Hoa\Event\Exception');
    }

    public function case_detach()
    {
        $this
            ->given(
                $source     = new \Mock\Hoa\Event\Listenable(),
                $listenerId = 'foo',
                $listener   = new SUT($source, ['foo', 'bar']),
                $callable   = function () {
                    return 42;
                },
                $listener->attach($listenerId, $callable)
            )
            ->when($result = $listener->detach($listenerId, $callable))
            ->then
                ->object($result)
                    ->isIdenticalTo($listener)
                ->array($listener->fire($listenerId, new LUT\Bucket()))
                    ->isEmpty();
    }

    public function case_detach_an_undefined_listener()
    {
        $this
            ->given(
                $source     = new \Mock\Hoa\Event\Listenable(),
                $listenerId = 'bar',
                $listener   = new SUT($source, ['foo', 'baz']),
                $callable   = function () { }
            )
            ->when($result = $listener->detach($listenerId, $callable))
            ->then
                ->object($result)
                    ->isIdenticalTo($listener);
    }

    public function case_detach_all()
    {
        $this
            ->given(
                $source     = new \Mock\Hoa\Event\Listenable(),
                $listenerId = 'foo',
                $listener   = new SUT($source, ['foo', 'bar'])
            )
            ->when($result = $listener->detachAll($listenerId))
            ->then
                ->object($result)
                    ->isIdenticalTo($listener)
                ->boolean($listener->listenerExists($listenerId))
                    ->isFalse();
    }

    public function case_detach_all_with_an_undefined_listener()
    {
        $this
            ->given(
                $source     = new \Mock\Hoa\Event\Listenable(),
                $listenerId = 'bar',
                $listener   = new SUT($source, ['foo', 'baz'])
            )
            ->when($result = $listener->detachAll($listenerId))
            ->then
                ->object($result)
                    ->isIdenticalTo($listener);
    }

    public function case_listener_exists()
    {
        $this
            ->given(
                $source   = new \Mock\Hoa\Event\Listenable(),
                $ids      = [],
                $listener = new SUT($source, $ids)
            )
            ->when($listener->addIds(['foo']))
            ->then
                ->boolean($listener->listenerExists('foo'))
                    ->isTrue()
                ->boolean($listener->listenerExists('bar'))
                    ->isFalse()

            ->when($listener->addIds(['bar']))
            ->then
                ->boolean($listener->listenerExists('bar'))
                    ->isTrue();
    }

    public function case_fire()
    {
        $self = $this;

        $this
            ->given(
                $source   = new \Mock\Hoa\Event\Listenable(),
                $ids      = ['foo', 'bar'],
                $listener = new SUT($source, $ids),

                $listenerId = 'foo',
                $bucket     = new LUT\Bucket(),
                $listener->attach(
                    $listenerId,
                    function (LUT\Bucket $receivedBucket) use ($self, $bucket, $source, &$called) {
                        $called = true;

                        $self
                            ->object($receivedBucket)
                                ->isIdenticalTo($bucket)
                            ->object($receivedBucket->getSource())
                                ->isIdenticalTo($source);

                        return 42;
                    }
                )
            )
            ->when($result = $listener->fire($listenerId, $bucket))
            ->then
                ->array($result)
                    ->isEqualTo([42])
                ->boolean($called)
                    ->isTrue();
    }

    public function case_fire_an_undefined_listenerId()
    {
        $this
            ->given(
                $source   = new \Mock\Hoa\Event\Listenable(),
                $ids      = [],
                $listener = new SUT($source, $ids)
            )
            ->exception(function () use ($listener) {
                $listener->fire('foo', new LUT\Bucket());
            })
                ->isInstanceOf('Hoa\Event\Exception');
    }
}
