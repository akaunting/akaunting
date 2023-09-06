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

namespace Hoa\Exception;

use Hoa\Consistency;
use Hoa\Event;

/**
 * Class \Hoa\Exception\Exception.
 *
 * Each exception must extend \Hoa\Exception\Exception.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Exception extends Idle implements Event\Source
{
    /**
     * Create an exception.
     * An exception is built with a formatted message, a code (an ID), and an
     * array that contains the list of formatted string for the message. If
     * chaining, we can add a previous exception.
     *
     * @param   string      $message      Formatted message.
     * @param   int         $code         Code (the ID).
     * @param   array       $arguments    Arguments to format message.
     * @param   \Throwable  $previous     Previous exception in chaining.
     */
    public function __construct(
        $message,
        $code      = 0,
        $arguments = [],
        $previous  = null
    ) {
        parent::__construct($message, $code, $arguments, $previous);

        if (false === Event::eventExists('hoa://Event/Exception')) {
            Event::register('hoa://Event/Exception', __CLASS__);
        }

        $this->send();

        return;
    }

    /**
     * Send the exception on hoa://Event/Exception.
     *
     * @return  void
     */
    public function send()
    {
        Event::notify(
            'hoa://Event/Exception',
            $this,
            new Event\Bucket($this)
        );

        return;
    }
}

/**
 * Flex entity.
 */
Consistency::flexEntity('Hoa\Exception\Exception');
