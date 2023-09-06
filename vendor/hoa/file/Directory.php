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

namespace Hoa\File;

use Hoa\Stream;

/**
 * Class \Hoa\File\Directory.
 *
 * Directory handler.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Directory extends Generic
{
    /**
     * Open for reading.
     *
     * @const string
     */
    const MODE_READ             = 'rb';

    /**
     * Open for reading and writing. If the directory does not exist, attempt to
     * create it.
     *
     * @const string
     */
    const MODE_CREATE           = 'xb';

    /**
     * Open for reading and writing. If the directory does not exist, attempt to
     * create it recursively.
     *
     * @const string
     */
    const MODE_CREATE_RECURSIVE = 'xrb';



    /**
     * Open a directory.
     *
     * @param   string  $streamName    Stream name.
     * @param   string  $mode          Open mode, see the self::MODE* constants.
     * @param   string  $context       Context ID (please, see the
     *                                 \Hoa\Stream\Context class).
     * @param   bool    $wait          Differ opening or not.
     */
    public function __construct(
        $streamName,
        $mode    = self::MODE_READ,
        $context = null,
        $wait    = false
    ) {
        $this->setMode($mode);
        parent::__construct($streamName, $context, $wait);

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
        if (false === is_dir($streamName)) {
            if ($this->getMode() == self::MODE_READ) {
                throw new Exception\FileDoesNotExist(
                    'Directory %s does not exist.',
                    0,
                    $streamName
                );
            } else {
                self::create(
                    $streamName,
                    $this->getMode(),
                    null !== $context
                        ? $context->getContext()
                        : null
                );
            }
        }

        $out = null;

        return $out;
    }

    /**
     * Close the current stream.
     *
     * @return  bool
     */
    protected function _close()
    {
        return true;
    }

    /**
     * Recursive copy of a directory.
     *
     * @param   string  $to       Destination path.
     * @param   bool    $force    Force to copy if the file $to already exists.
     *                            Use the \Hoa\Stream\IStream\Touchable::*OVERWRITE
     *                            constants.
     * @return  bool
     * @throws  \Hoa\File\Exception
     */
    public function copy($to, $force = Stream\IStream\Touchable::DO_NOT_OVERWRITE)
    {
        if (empty($to)) {
            throw new Exception(
                'The destination path (to copy) is empty.',
                1
            );
        }

        $from       = $this->getStreamName();
        $fromLength = strlen($from) + 1;
        $finder     = new Finder();
        $finder->in($from);

        self::create($to, self::MODE_CREATE_RECURSIVE);

        foreach ($finder as $file) {
            $relative = substr($file->getPathname(), $fromLength);
            $_to      = $to . DS . $relative;

            if (true === $file->isDir()) {
                self::create($_to, self::MODE_CREATE);

                continue;
            }

            // This is not possible to do `$file->open()->copy();
            // $file->close();` because the file will be opened in read and
            // write mode. In a PHAR for instance, this operation is
            // forbidden. So a special care must be taken to open file in read
            // only mode.
            $handle = null;

            if (true === $file->isFile()) {
                $handle = new Read($file->getPathname());
            } elseif (true === $file->isDir()) {
                $handle = new Directory($file->getPathName());
            } elseif (true === $file->isLink()) {
                $handle = new Link\Read($file->getPathName());
            }

            if (null !== $handle) {
                $handle->copy($_to, $force);
                $handle->close();
            }
        }

        return true;
    }

    /**
     * Delete a directory.
     *
     * @return  bool
     */
    public function delete()
    {
        $from   = $this->getStreamName();
        $finder = new Finder();
        $finder->in($from)
               ->childFirst();

        foreach ($finder as $file) {
            $file->open()->delete();
            $file->close();
        }

        if (null === $this->getStreamContext()) {
            return @rmdir($from);
        }

        return @rmdir($from, $this->getStreamContext()->getContext());
    }

    /**
     * Create a directory.
     *
     * @param   string  $name       Directory name.
     * @param   string  $mode       Create mode. Please, see the self::MODE_CREATE*
     *                              constants.
     * @param   string  $context    Context ID (please, see the
     *                              \Hoa\Stream\Context class).
     * @return  bool
     * @throws  \Hoa\File\Exception
     */
    public static function create(
        $name,
        $mode    = self::MODE_CREATE_RECURSIVE,
        $context = null
    ) {
        if (true === is_dir($name)) {
            return true;
        }

        if (empty($name)) {
            return false;
        }

        if (null !== $context) {
            if (false === Stream\Context::contextExists($context)) {
                throw new Exception(
                    'Context %s was not previously declared, cannot retrieve ' .
                    'this context.',
                    2,
                    $context
                );
            } else {
                $context = Stream\Context::getInstance($context);
            }
        }

        if (null === $context) {
            return @mkdir(
                $name,
                0755,
                self::MODE_CREATE_RECURSIVE === $mode
            );
        }

        return @mkdir(
            $name,
            0755,
            self::MODE_CREATE_RECURSIVE === $mode,
            $context->getContext()
        );
    }
}
