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
 * Class \Hoa\File\Generic.
 *
 * Describe a super-file.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
abstract class Generic
    extends    Stream
    implements Stream\IStream\Pathable,
               Stream\IStream\Statable,
               Stream\IStream\Touchable
{
    /**
     * Mode.
     *
     * @var string
     */
    protected $_mode = null;



    /**
     * Get filename component of path.
     *
     * @return  string
     */
    public function getBasename()
    {
        return basename($this->getStreamName());
    }

    /**
     * Get directory name component of path.
     *
     * @return  string
     */
    public function getDirname()
    {
        return dirname($this->getStreamName());
    }

    /**
     * Get size.
     *
     * @return  int
     */
    public function getSize()
    {
        if (false === $this->getStatistic()) {
            return false;
        }

        return filesize($this->getStreamName());
    }

    /**
     * Get informations about a file.
     *
     * @return  array
     */
    public function getStatistic()
    {
        return fstat($this->getStream());
    }

    /**
     * Get last access time of file.
     *
     * @return  int
     */
    public function getATime()
    {
        return fileatime($this->getStreamName());
    }

    /**
     * Get inode change time of file.
     *
     * @return  int
     */
    public function getCTime()
    {
        return filectime($this->getStreamName());
    }

    /**
     * Get file modification time.
     *
     * @return  int
     */
    public function getMTime()
    {
        return filemtime($this->getStreamName());
    }

    /**
     * Get file group.
     *
     * @return  int
     */
    public function getGroup()
    {
        return filegroup($this->getStreamName());
    }

    /**
     * Get file owner.
     *
     * @return  int
     */
    public function getOwner()
    {
        return fileowner($this->getStreamName());
    }

    /**
     * Get file permissions.
     *
     * @return  int
     */
    public function getPermissions()
    {
        return fileperms($this->getStreamName());
    }

    /**
     * Get file permissions as a string.
     * Result sould be interpreted like this:
     *     * s: socket;
     *     * l: symbolic link;
     *     * -: regular;
     *     * b: block special;
     *     * d: directory;
     *     * c: character special;
     *     * p: FIFO pipe;
     *     * u: unknown.
     *
     * @return  string
     */
    public function getReadablePermissions()
    {
        $p = $this->getPermissions();

        if (($p & 0xC000) == 0xC000) {
            $out = 's';
        } elseif (($p & 0xA000) == 0xA000) {
            $out = 'l';
        } elseif (($p & 0x8000) == 0x8000) {
            $out = '-';
        } elseif (($p & 0x6000) == 0x6000) {
            $out = 'b';
        } elseif (($p & 0x4000) == 0x4000) {
            $out = 'd';
        } elseif (($p & 0x2000) == 0x2000) {
            $out = 'c';
        } elseif (($p & 0x1000) == 0x1000) {
            $out = 'p';
        } else {
            $out = 'u';
        }

        $out .=
            (($p & 0x0100) ? 'r' : '-') .
            (($p & 0x0080) ? 'w' : '-') .
            (($p & 0x0040) ?
            (($p & 0x0800) ? 's' : 'x') :
            (($p & 0x0800) ? 'S' : '-')) .
            (($p & 0x0020) ? 'r' : '-') .
            (($p & 0x0010) ? 'w' : '-') .
            (($p & 0x0008) ?
            (($p & 0x0400) ? 's' : 'x') :
            (($p & 0x0400) ? 'S' : '-')) .
            (($p & 0x0004) ? 'r' : '-') .
            (($p & 0x0002) ? 'w' : '-') .
            (($p & 0x0001) ?
            (($p & 0x0200) ? 't' : 'x') :
            (($p & 0x0200) ? 'T' : '-'));

        return $out;
    }

    /**
     * Check if the file is readable.
     *
     * @return  bool
     */
    public function isReadable()
    {
        return is_readable($this->getStreamName());
    }

    /**
     * Check if the file is writable.
     *
     * @return  bool
     */
    public function isWritable()
    {
        return is_writable($this->getStreamName());
    }

    /**
     * Check if the file is executable.
     *
     * @return  bool
     */
    public function isExecutable()
    {
        return is_executable($this->getStreamName());
    }

    /**
     * Clear file status cache.
     *
     * @return  void
     */
    public function clearStatisticCache()
    {
        clearstatcache(true, $this->getStreamName());

        return;
    }

    /**
     * Clear all files status cache.
     *
     * @return  void
     */
    public static function clearAllStatisticCaches()
    {
        clearstatcache();

        return;
    }

    /**
     * Set access and modification time of file.
     *
     * @param   int     $time     Time. If equals to -1, time() should be used.
     * @param   int     $atime    Access time. If equals to -1, $time should be
     *                            used.
     * @return  bool
     */
    public function touch($time = -1, $atime = -1)
    {
        if ($time == -1) {
            $time  = time();
        }

        if ($atime == -1) {
            $atime = $time;
        }

        return touch($this->getStreamName(), $time, $atime);
    }

    /**
     * Copy file.
     * Return the destination file path if succeed, false otherwise.
     *
     * @param   string  $to       Destination path.
     * @param   bool    $force    Force to copy if the file $to already exists.
     *                            Use the \Hoa\Stream\IStream\Touchable::*OVERWRITE
     *                            constants.
     * @return  bool
     */
    public function copy($to, $force = Stream\IStream\Touchable::DO_NOT_OVERWRITE)
    {
        $from = $this->getStreamName();

        if ($force === Stream\IStream\Touchable::DO_NOT_OVERWRITE &&
            true   === file_exists($to)) {
            return true;
        }

        if (null === $this->getStreamContext()) {
            return @copy($from, $to);
        }

        return @copy($from, $to, $this->getStreamContext()->getContext());
    }

    /**
     * Move a file.
     *
     * @param   string  $name     New name.
     * @param   bool    $force    Force to move if the file $name already
     *                            exists.
     *                            Use the \Hoa\Stream\IStream\Touchable::*OVERWRITE
     *                            constants.
     * @param   bool    $mkdir    Force to make directory if does not exist.
     *                            Use the \Hoa\Stream\IStream\Touchable::*DIRECTORY
     *                            constants.
     * @return  bool
     */
    public function move(
        $name,
        $force = Stream\IStream\Touchable::DO_NOT_OVERWRITE,
        $mkdir = Stream\IStream\Touchable::DO_NOT_MAKE_DIRECTORY
    ) {
        $from = $this->getStreamName();

        if ($force === Stream\IStream\Touchable::DO_NOT_OVERWRITE &&
            true   === file_exists($name)) {
            return false;
        }

        if (Stream\IStream\Touchable::MAKE_DIRECTORY === $mkdir) {
            Directory::create(
                dirname($name),
                Directory::MODE_CREATE_RECURSIVE
            );
        }

        if (null === $this->getStreamContext()) {
            return @rename($from, $name);
        }

        return @rename($from, $name, $this->getStreamContext()->getContext());
    }

    /**
     * Delete a file.
     *
     * @return  bool
     */
    public function delete()
    {
        if (null === $this->getStreamContext()) {
            return @unlink($this->getStreamName());
        }

        return @unlink(
            $this->getStreamName(),
            $this->getStreamContext()->getContext()
        );
    }

    /**
     * Change file group.
     *
     * @param   mixed   $group    Group name or number.
     * @return  bool
     */
    public function changeGroup($group)
    {
        return chgrp($this->getStreamName(), $group);
    }

    /**
     * Change file mode.
     *
     * @param   int     $mode    Mode (in octal!).
     * @return  bool
     */
    public function changeMode($mode)
    {
        return chmod($this->getStreamName(), $mode);
    }

    /**
     * Change file owner.
     *
     * @param   mixed   $user    User.
     * @return  bool
     */
    public function changeOwner($user)
    {
        return chown($this->getStreamName(), $user);
    }

    /**
     * Change the current umask.
     *
     * @param   int     $umask    Umask (in octal!). If null, given the current
     *                            umask value.
     * @return  int
     */
    public static function umask($umask = null)
    {
        if (null === $umask) {
            return umask();
        }

        return umask($umask);
    }

    /**
     * Check if it is a file.
     *
     * @return  bool
     */
    public function isFile()
    {
        return is_file($this->getStreamName());
    }

    /**
     * Check if it is a link.
     *
     * @return  bool
     */
    public function isLink()
    {
        return is_link($this->getStreamName());
    }

    /**
     * Check if it is a directory.
     *
     * @return  bool
     */
    public function isDirectory()
    {
        return is_dir($this->getStreamName());
    }

    /**
     * Check if it is a socket.
     *
     * @return  bool
     */
    public function isSocket()
    {
        return filetype($this->getStreamName()) == 'socket';
    }

    /**
     * Check if it is a FIFO pipe.
     *
     * @return  bool
     */
    public function isFIFOPipe()
    {
        return filetype($this->getStreamName()) == 'fifo';
    }

    /**
     * Check if it is character special file.
     *
     * @return  bool
     */
    public function isCharacterSpecial()
    {
        return filetype($this->getStreamName()) == 'char';
    }

    /**
     * Check if it is block special.
     *
     * @return  bool
     */
    public function isBlockSpecial()
    {
        return filetype($this->getStreamName()) == 'block';
    }

    /**
     * Check if it is an unknown type.
     *
     * @return  bool
     */
    public function isUnknown()
    {
        return filetype($this->getStreamName()) == 'unknown';
    }

    /**
     * Set the open mode.
     *
     * @param   string     $mode    Open mode. Please, see the child::MODE_*
     *                              constants.
     * @return  string
     */
    protected function setMode($mode)
    {
        $old         = $this->_mode;
        $this->_mode = $mode;

        return $old;
    }

    /**
     * Get the open mode.
     *
     * @return  string
     */
    public function getMode()
    {
        return $this->_mode;
    }

    /**
     * Get inode.
     *
     * @return  int
     */
    public function getINode()
    {
        return fileinode($this->getStreamName());
    }

    /**
     * Check if the system is case sensitive or not.
     *
     * @return  bool
     */
    public static function isCaseSensitive()
    {
        return !(
            file_exists(mb_strtolower(__FILE__)) &&
            file_exists(mb_strtoupper(__FILE__))
        );
    }

    /**
     * Get a canonicalized absolute pathname.
     *
     * @return  string
     */
    public function getRealPath()
    {
        if (false === $out = realpath($this->getStreamName())) {
            return $this->getStreamName();
        }

        return $out;
    }

    /**
     * Get file extension (if exists).
     *
     * @return  string
     */
    public function getExtension()
    {
        return pathinfo(
            $this->getStreamName(),
            PATHINFO_EXTENSION
        );
    }

    /**
     * Get filename without extension.
     *
     * @return  string
     */
    public function getFilename()
    {
        $file = basename($this->getStreamName());

        if (defined('PATHINFO_FILENAME')) {
            return pathinfo($file, PATHINFO_FILENAME);
        }

        if (strstr($file, '.')) {
            return substr($file, 0, strrpos($file, '.'));
        }

        return $file;
    }
}
