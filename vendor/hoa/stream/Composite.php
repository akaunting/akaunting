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

namespace Hoa\Stream;

/**
 * Class \Hoa\Stream\Composite.
 *
 * Declare a composite stream, i.e. a stream that uses a stream.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
abstract class Composite
{
    /**
     * Current stream.
     *
     * @var mixed
     */
    protected $_stream      = null;

    /**
     * Inner stream.
     *
     * @var \Hoa\Stream
     */
    protected $_innerStream = null;



    /**
     * Set current stream.
     *
     * @param   object  $stream    Current stream.
     * @return  object
     */
    protected function setStream($stream)
    {
        $old           = $this->_stream;
        $this->_stream = $stream;

        return $old;
    }

    /**
     * Get current stream.
     *
     * @return  object
     */
    public function getStream()
    {
        return $this->_stream;
    }

    /**
     * Set inner stream.
     *
     * @param   \Hoa\Stream  $innerStream    Inner stream.
     * @return  \Hoa\Stream
     */
    protected function setInnerStream(Stream $innerStream)
    {
        $old                = $this->_innerStream;
        $this->_innerStream = $innerStream;

        return $old;
    }

    /**
     * Get inner stream.
     *
     * @return  \Hoa\Stream
     */
    public function getInnerStream()
    {
        return $this->_innerStream;
    }
}
