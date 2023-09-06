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
use Hoa\Event\Listens as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Event\Test\Unit\Listens.
 *
 * Test suite of the listens trait.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Listens extends Test\Unit\Suite
{
    public function case_set_listener()
    {
        $this
            ->given(
                $listenable = new _Listenable(),
                $listener   = new LUT\Listener($listenable, ['foo'])
            )
            ->when($result = $listenable->_setListener($listener))
            ->then
                ->variable($result)
                    ->isNull();
    }

    public function case_get_listener()
    {
        $this
            ->given(
                $listenable = new _Listenable(),
                $listener   = new LUT\Listener($listenable, ['foo']),
                $listenable->_setListener($listener)
            )
            ->when($result = $listenable->_getListener())
            ->then
                ->object($result)
                    ->isIdenticalTo($listener);
    }

    public function case_on()
    {
        $this
            ->given(
                $listenable = new _Listenable(),
                $listener   = new LUT\Listener($listenable, ['foo']),
                $listenable->_setListener($listener),
                $callable   = function () use (&$called) {
                    $called = true;

                    return 42;
                }
            )
            ->when($result = $listenable->on('foo', $callable))
            ->then
                ->object($result)
                    ->isIdenticalTo($listenable)

            ->when($listenable->doSomethingThatFires())
            ->then
                ->boolean($called)
                    ->isTrue();
    }

    public function case_on_unregistered_listener()
    {
        $this
            ->given(
                $listenable = new _Listenable(),
                $listener   = new LUT\Listener($listenable, ['foo']),
                $listenable->_setListener($listener)
            )
            ->exception(function () use ($listenable) {
                $listenable->on('bar', null);
            })
                ->isInstanceOf('Hoa\Event\Exception');
    }
}

class _Listenable implements LUT\Listenable
{
    use SUT;

    public function _setListener(LUT\Listener $listener)
    {
        return $this->setListener($listener);
    }

    public function _getListener()
    {
        return $this->getListener();
    }

    public function doSomethingThatFires()
    {
        $this->getListener()->fire('foo', new LUT\Bucket('bar'));

        return;
    }
}
