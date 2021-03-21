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
 * Class \Hoa\Event\Listener.
 *
 * A contrario of events, listeners are synchronous, identified at use and
 * useful for close interactions between one or some components.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Listener
{
    /**
     * Source of listener (for Bucket).
     *
     * @var \Hoa\Event\Listenable
     */
    protected $_source    = null;

    /**
     * All listener IDs and associated listeners.
     *
     * @var array
     */
    protected $_callables = [];



    /**
     * Build a listener.
     *
     * @param   \Hoa\Event\Listenable  $source    Source (for Bucket).
     * @param   array                  $ids       Accepted ID.
     */
    public function __construct(Listenable $source, array $ids)
    {
        $this->_source = $source;
        $this->addIds($ids);

        return;
    }

    /**
     * Add acceptable ID (or reset).
     *
     * @param   array  $ids    Accepted ID.
     * @return  void
     */
    public function addIds(array $ids)
    {
        foreach ($ids as $id) {
            $this->_callables[$id] = [];
        }

        return;
    }

    /**
     * Attach a callable to a listenable component.
     *
     * @param   string  $listenerId    Listener ID.
     * @param   mixed   $callable      Callable.
     * @return  \Hoa\Event\Listener
     * @throws  \Hoa\Event\Exception
     */
    public function attach($listenerId, $callable)
    {
        if (false === $this->listenerExists($listenerId)) {
            throw new Exception(
                'Cannot listen %s because it is not defined.',
                0,
                $listenerId
            );
        }

        $callable                                            = xcallable($callable);
        $this->_callables[$listenerId][$callable->getHash()] = $callable;

        return $this;
    }

    /**
     * Detach a callable from a listenable component.
     *
     * @param   string  $listenerId    Listener ID.
     * @param   mixed   $callable      Callable.
     * @return  \Hoa\Event\Listener
     */
    public function detach($listenerId, $callable)
    {
        unset($this->_callables[$listenerId][xcallable($callable)->getHash()]);

        return $this;
    }

    /**
     * Detach all callables from a listenable component.
     *
     * @param  string  $listenerId    Listener ID.
     * @return \Hoa\Event\Listener
     */
    public function detachAll($listenerId)
    {
        unset($this->_callables[$listenerId]);

        return $this;
    }

    /**
     * Check if a listener exists.
     *
     * @param   string  $listenerId    Listener ID.
     * @return  bool
     */
    public function listenerExists($listenerId)
    {
        return array_key_exists($listenerId, $this->_callables);
    }

    /**
     * Send/fire a bucket to a listener.
     *
     * @param   string             $listenerId    Listener ID.
     * @param   \Hoa\Event\Bucket  $data          Data.
     * @return  array
     * @throws  \Hoa\Event\Exception
     */
    public function fire($listenerId, Bucket $data)
    {
        if (false === $this->listenerExists($listenerId)) {
            throw new Exception(
                'Cannot fire on %s because it is not defined.',
                1,
                $listenerId
            );
        }

        $data->setSource($this->_source);
        $out = [];

        foreach ($this->_callables[$listenerId] as $callable) {
            $out[] = $callable($data);
        }

        return $out;
    }
}
