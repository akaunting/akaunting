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

namespace Hoa\Stream\IStream;

/**
 * Interface \Hoa\Stream\IStream\In.
 *
 * Interface for input.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
interface In extends Stream
{
    /**
     * Test for end-of-stream.
     *
     * @return  bool
     */
    public function eof();

    /**
     * Read n characters.
     *
     * @param   int     $length    Length.
     * @return  string
     */
    public function read($length);

    /**
     * Alias of $this->read().
     *
     * @param   int     $length    Length.
     * @return  string
     */
    public function readString($length);

    /**
     * Read a character.
     * It could be equivalent to $this->read(1).
     *
     * @return  string
     */
    public function readCharacter();

    /**
     * Read a boolean.
     *
     * @return  bool
     */
    public function readBoolean();

    /**
     * Read an integer.
     *
     * @param   int     $length    Length.
     * @return  int
     */
    public function readInteger($length = 1);

    /**
     * Read a float.
     *
     * @param   int     $length    Length.
     * @return  float
     */
    public function readFloat($length = 1);

    /**
     * Read an array.
     * In most cases, it could be an alias to the $this->scanf() method.
     *
     * @param   mixed   $argument    Argument (because the behavior is very
     *                               different according to the implementation).
     * @return  array
     */
    public function readArray($argument = null);

    /**
     * Read a line.
     *
     * @return  string
     */
    public function readLine();

    /**
     * Read all, i.e. read as much as possible.
     *
     * @param   int  $offset    Offset.
     * @return  string
     */
    public function readAll($offset = 0);

    /**
     * Parse input from a stream according to a format.
     *
     * @param   string  $format    Format (see printf's formats).
     * @return  array
     */
    public function scanf($format);
}
