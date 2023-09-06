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

namespace Hoa\File\Link;

use Hoa\File;
use Hoa\Stream;

/**
 * Class \Hoa\File\Link\ReadWrite.
 *
 * File handler.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class          ReadWrite
    extends    Link
    implements Stream\IStream\In,
               Stream\IStream\Out
{
    /**
     * Open a file.
     *
     * @param   string  $streamName    Stream name.
     * @param   string  $mode          Open mode, see the parent::MODE_* constants.
     * @param   string  $context       Context ID (please, see the
     *                                 \Hoa\Stream\Context class).
     * @param   bool    $wait          Differ opening or not.
     */
    public function __construct(
        $streamName,
        $mode    = parent::MODE_APPEND_READ_WRITE,
        $context = null,
        $wait    = false
    ) {
        parent::__construct($streamName, $mode, $context, $wait);

        return;
    }

    /**
     * Open the stream and return the associated resource.
     *
     * @param   string               $streamName    Stream name (e.g. path or URL).
     * @param   \Hoa\Stream\Context  $context       Context.
     * @return  resource
     * @throws  \Hoa\File\Exception\FileDoesNotExist
     * @throws  \Hoa\File\Exception
     */
    protected function &_open($streamName, Stream\Context $context = null)
    {
        static $createModes = [
            parent::MODE_READ_WRITE,
            parent::MODE_TRUNCATE_READ_WRITE,
            parent::MODE_APPEND_READ_WRITE,
            parent::MODE_CREATE_READ_WRITE
        ];

        if (!in_array($this->getMode(), $createModes)) {
            throw new File\Exception(
                'Open mode are not supported; given %d. Only %s are supported.',
                0,
                [$this->getMode(), implode(', ', $createModes)]
            );
        }

        preg_match('#^(\w+)://#', $streamName, $match);

        if (((isset($match[1]) && $match[1] == 'file') || !isset($match[1])) &&
            !file_exists($streamName) &&
            parent::MODE_READ_WRITE == $this->getMode()) {
            throw new File\Exception\FileDoesNotExist(
                'File %s does not exist.',
                1,
                $streamName
            );
        }

        $out = parent::_open($streamName, $context);

        return $out;
    }

    /**
     * Test for end-of-file.
     *
     * @return  bool
     */
    public function eof()
    {
        return feof($this->getStream());
    }

    /**
     * Read n characters.
     *
     * @param   int     $length    Length.
     * @return  string
     * @throws  \Hoa\File\Exception
     */
    public function read($length)
    {
        if (0 > $length) {
            throw new File\Exception(
                'Length must be greater than 0, given %d.',
                2,
                $length
            );
        }

        return fread($this->getStream(), $length);
    }

    /**
     * Alias of $this->read().
     *
     * @param   int     $length    Length.
     * @return  string
     */
    public function readString($length)
    {
        return $this->read($length);
    }

    /**
     * Read a character.
     *
     * @return  string
     */
    public function readCharacter()
    {
        return fgetc($this->getStream());
    }

    /**
     * Read a boolean.
     *
     * @return  bool
     */
    public function readBoolean()
    {
        return (bool) $this->read(1);
    }

    /**
     * Read an integer.
     *
     * @param   int     $length    Length.
     * @return  int
     */
    public function readInteger($length = 1)
    {
        return (int) $this->read($length);
    }

    /**
     * Read a float.
     *
     * @param   int     $length    Length.
     * @return  float
     */
    public function readFloat($length = 1)
    {
        return (float) $this->read($length);
    }

    /**
     * Read an array.
     * Alias of the $this->scanf() method.
     *
     * @param   string  $format    Format (see printf's formats).
     * @return  array
     */
    public function readArray($format = null)
    {
        return $this->scanf($format);
    }

    /**
     * Read a line.
     *
     * @return  string
     */
    public function readLine()
    {
        return fgets($this->getStream());
    }

    /**
     * Read all, i.e. read as much as possible.
     *
     * @param   int  $offset    Offset.
     * @return  string
     */
    public function readAll($offset = 0)
    {
        return stream_get_contents($this->getStream(), -1, $offset);
    }

    /**
     * Parse input from a stream according to a format.
     *
     * @param   string  $format    Format (see printf's formats).
     * @return  array
     */
    public function scanf($format)
    {
        return fscanf($this->getStream(), $format);
    }

    /**
     * Write n characters.
     *
     * @param   string  $string    String.
     * @param   int     $length    Length.
     * @return  mixed
     * @throws  \Hoa\File\Exception
     */
    public function write($string, $length)
    {
        if (0 > $length) {
            throw new File\Exception(
                'Length must be greater than 0, given %d.',
                3,
                $length
            );
        }

        return fwrite($this->getStream(), $string, $length);
    }

    /**
     * Write a string.
     *
     * @param   string  $string    String.
     * @return  mixed
     */
    public function writeString($string)
    {
        $string = (string) $string;

        return $this->write($string, strlen($string));
    }

    /**
     * Write a character.
     *
     * @param   string  $char    Character.
     * @return  mixed
     */
    public function writeCharacter($char)
    {
        return $this->write((string) $char[0], 1);
    }

    /**
     * Write a boolean.
     *
     * @param   bool    $boolean    Boolean.
     * @return  mixed
     */
    public function writeBoolean($boolean)
    {
        return $this->write((string) (bool) $boolean, 1);
    }

    /**
     * Write an integer.
     *
     * @param   int     $integer    Integer.
     * @return  mixed
     */
    public function writeInteger($integer)
    {
        $integer = (string) (int) $integer;

        return $this->write($integer, strlen($integer));
    }

    /**
     * Write a float.
     *
     * @param   float   $float    Float.
     * @return  mixed
     */
    public function writeFloat($float)
    {
        $float = (string) (float) $float;

        return $this->write($float, strlen($float));
    }

    /**
     * Write an array.
     *
     * @param   array   $array    Array.
     * @return  mixed
     */
    public function writeArray(array $array)
    {
        $array = var_export($array, true);

        return $this->write($array, strlen($array));
    }

    /**
     * Write a line.
     *
     * @param   string  $line    Line.
     * @return  mixed
     */
    public function writeLine($line)
    {
        if (false === $n = strpos($line, "\n")) {
            return $this->write($line . "\n", strlen($line) + 1);
        }

        ++$n;

        return $this->write(substr($line, 0, $n), $n);
    }

    /**
     * Write all, i.e. as much as possible.
     *
     * @param   string  $string    String.
     * @return  mixed
     */
    public function writeAll($string)
    {
        return $this->write($string, strlen($string));
    }

    /**
     * Truncate a file to a given length.
     *
     * @param   int     $size    Size.
     * @return  bool
     */
    public function truncate($size)
    {
        return ftruncate($this->getStream(), $size);
    }
}
