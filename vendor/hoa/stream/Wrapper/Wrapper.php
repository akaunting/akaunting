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

namespace Hoa\Stream\Wrapper;

use Hoa\Consistency;

/**
 * Class \Hoa\Stream\Wrapper.
 *
 * Manipulate wrappers.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Wrapper
{
    /**
     * Register a wrapper.
     *
     * @param   string  $protocol     The wrapper name to be registered.
     * @param   string  $className    Class name which implements the protocol.
     * @param   int     $flags        Should be set to `STREAM_IS_URL` if
     *                                `$protocol` is a URL protocol. Default is 0,
     *                                local stream.
     * @return  bool
     * @throws  \Hoa\Stream\Wrapper\Exception
     */
    public static function register($protocol, $className, $flags = 0)
    {
        if (true === self::isRegistered($protocol)) {
            throw new Exception(
                'The protocol %s is already registered.',
                0,
                $protocol
            );
        }

        if (false === class_exists($className)) {
            throw new Exception(
                'Cannot use the %s class for the implementation of ' .
                'the %s protocol because it is not found.',
                1,
                [$className, $protocol]
            );
        }

        return stream_wrapper_register($protocol, $className, $flags);
    }

    /**
     * Unregister a wrapper.
     *
     * @param   string  $protocol    The wrapper name to be unregistered.
     * @return  bool
     */
    public static function unregister($protocol)
    {
        // Silent errors if `$protocol` does not exist. This function already
        // returns `false` in this case, which is the strict expected
        // behaviour.
        return @stream_wrapper_unregister($protocol);
    }

    /**
     * Restore a previously unregistered build-in wrapper.
     *
     * @param   string  $protocol    The wrapper name to be restored.
     * @return  bool
     */
    public static function restore($protocol)
    {
        // Silent errors if `$protocol` does not exist. This function already
        // returns `false` in this case, which is the strict expected
        // behaviour.
        return @stream_wrapper_restore($protocol);
    }

    /**
     * Check if a protocol is registered or not.
     *
     * @param   string  $protocol    Protocol name.
     * @return  bool
     */
    public static function isRegistered($protocol)
    {
        return in_array($protocol, self::getRegistered());
    }

    /**
     * Get all registered wrapper.
     *
     * @return  array
     */
    public static function getRegistered()
    {
        return stream_get_wrappers();
    }
}

/**
 * Flex entity.
 */
Consistency::flexEntity('Hoa\Stream\Wrapper\Wrapper');
