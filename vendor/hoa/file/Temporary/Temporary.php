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

namespace Hoa\File\Temporary;

use Hoa\Consistency;
use Hoa\File;
use Hoa\Stream;

/**
 * Class \Hoa\File\Temporary.
 *
 * Temporary file handler.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Temporary extends File
{
    /**
     * Temporary file index.
     *
     * @var int
     */
    private static $_i = 0;



    /**
     * Open a temporary file.
     *
     * @param   string  $streamName    Stream name (or file descriptor).
     * @param   string  $mode          Open mode, see the parent::MODE_*
     *                                 constants.
     * @param   string  $context       Context ID (please, see the
     *                                 \Hoa\Stream\Context class).
     * @param   bool    $wait          Differ opening or not.
     */
    public function __construct(
        $streamName,
        $mode,
        $context = null,
        $wait    = false
    ) {
        if (null === $streamName) {
            $streamName = 'hoa://Library/File/Temporary.php#' . self::$_i++;
        }

        parent::__construct($streamName, $mode, $context, $wait);

        return;
    }

    /**
     * Open the stream and return the associated resource.
     *
     * @param   string              $streamName    Stream name (here, it is
     *                                             null).
     * @param   \Hoa\Stream\Context  $context       Context.
     * @return  resource
     * @throws  \Hoa\File\Exception
     */
    protected function &_open($streamName, Stream\Context $context = null)
    {
        if (false === $out = @tmpfile()) {
            throw new File\Exception(
                'Failed to open a temporary stream.',
                0
            );
        }

        return $out;
    }

    /**
     * Create a unique temporary file, i.e. a file with a unique filename. It is
     * different of calling $this->__construct() that will create a temporary
     * file that will be destroy when calling the $this->close() method.
     *
     * @param   string  $directory    Directory where the temporary filename
     *                                will be created. If the directory does not
     *                                exist, it may generate a file in the
     *                                system's temporary directory.
     * @param   string  $prefix       Prefix of the generated temporary
     *                                filename.
     * @return  string
     */
    public static function create($directory = null, $prefix = '__hoa_')
    {
        if (null  === $directory ||
            false === is_dir($directory)) {
            $directory = static::getTemporaryDirectory();
        }

        return tempnam($directory, $prefix);
    }

    /**
     * Get the directory path used for temporary files.
     *
     * @return  string
     */
    public static function getTemporaryDirectory()
    {
        return sys_get_temp_dir();
    }
}

/**
 * Flex entity.
 */
Consistency::flexEntity('Hoa\File\Temporary\Temporary');
