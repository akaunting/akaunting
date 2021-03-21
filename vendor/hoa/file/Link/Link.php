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

use Hoa\Consistency;
use Hoa\File;

/**
 * Class \Hoa\File\Link.
 *
 * Link handler.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Link extends File
{
    /**
     * Open a link.
     *
     * @param   string  $streamName    Stream name.
     * @param   string  $mode          Open mode, see the parent::MODE_*
     *                                 constants.
     * @param   string  $context       Context ID (please, see the
     *                                 \Hoa\Stream\Context class).
     * @param   bool    $wait          Differ opening or not.
     * @throws  \Hoa\File\Exception
     */
    public function __construct(
        $streamName,
        $mode,
        $context = null,
        $wait    = false
    ) {
        if (!is_link($streamName)) {
            throw new File\Exception(
                'File %s is not a link.',
                0,
                $streamName
            );
        }

        parent::__construct($streamName, $mode, $context, $wait);

        return;
    }

    /**
     * Get informations about a link.
     *
     * @return  array
     */
    public function getStatistic()
    {
        return lstat($this->getStreamName());
    }

    /**
     * Change file group.
     *
     * @param   mixed   $group    Group name or number.
     * @return  bool
     */
    public function changeGroup($group)
    {
        return lchgrp($this->getStreamName(), $group);
    }

    /**
     * Change file owner.
     *
     * @param   mixed   $user   User.
     * @return  bool
     */
    public function changeOwner($user)
    {
        return lchown($this->getStreamName(), $user);
    }

    /**
     * Get file permissions.
     *
     * @return  int
     */
    public function getPermissions()
    {
        return 41453; // i.e. lrwxr-xr-x
    }

    /**
     * Get the target of a symbolic link.
     *
     * @return  \Hoa\File\Generic
     * @throws  \Hoa\File\Exception
     */
    public function getTarget()
    {
        $target    = dirname($this->getStreamName()) . DS .
                     $this->getTargetName();
        $context   = null !== $this->getStreamContext()
                         ? $this->getStreamContext()->getCurrentId()
                         : null;

        if (true === is_link($target)) {
            return new ReadWrite(
                $target,
                File::MODE_APPEND_READ_WRITE,
                $context
            );
        } elseif (true === is_file($target)) {
            return new File\ReadWrite(
                $target,
                File::MODE_APPEND_READ_WRITE,
                $context
            );
        } elseif (true === is_dir($target)) {
            return new File\Directory(
                $target,
                File::MODE_READ,
                $context
            );
        }

        throw new File\Exception(
            'Cannot find an appropriated object that matches with ' .
            'path %s when defining it.',
            1,
            $target
        );
    }

    /**
     * Get the target name of a symbolic link.
     *
     * @return  string
     */
    public function getTargetName()
    {
        return readlink($this->getStreamName());
    }

    /**
     * Create a link.
     *
     * @param   string  $name      Link name.
     * @param   string  $target    Target name.
     * @return  bool
     */
    public static function create($name, $target)
    {
        if (false != linkinfo($name)) {
            return true;
        }

        return symlink($target, $name);
    }
}

/**
 * Flex entity.
 */
Consistency::flexEntity('Hoa\File\Link\Link');
