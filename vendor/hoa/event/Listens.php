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

namespace Hoa\Event;

/**
 * Trait \Hoa\Event\Listens.
 *
 * Implementation of a listener.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
trait Listens
{
    /**
     * Listener instance.
     *
     * @var \Hoa\Event\Listener
     */
    protected $_listener = null;



    /**
     * Attach a callable to a listenable component.
     *
     * @param   string  $listenerId    Listener ID.
     * @param   mixed   $callable      Callable.
     * @return  \Hoa\Event\Listenable
     */
    public function on($listenerId, $callable)
    {
        $listener = $this->getListener();

        if (null === $listener) {
            throw new Exception(
                'Cannot attach a callable to the listener %s because ' .
                'it has not been initialized yet.',
                0,
                get_class($this)
            );
        }

        $listener->attach($listenerId, $callable);

        return $this;
    }

    /**
     * Set listener.
     *
     * @param  \Hoa\Event\Listener  $listener    Listener.
     * @return \Hoa\Event\Listener
     */
    protected function setListener(Listener $listener)
    {
        $old             = $this->_listener;
        $this->_listener = $listener;

        return $old;
    }

    /**
     * Get listener.
     *
     * @return \Hoa\Event\Listener
     */
    protected function getListener()
    {
        return $this->_listener;
    }
}
