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
 * Interface \Hoa\Stream\IStream\Statable.
 *
 * Interface for statable input/output.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
interface Statable extends Stream
{
    /**
     * Size is undefined.
     *
     * @const int
     */
    const SIZE_UNDEFINED = -1;

    /**
     * Get size.
     *
     * @return  int
     */
    public function getSize();

    /**
     * Get informations about a file.
     *
     * @return  array
     */
    public function getStatistic();

    /**
     * Get last access time of file.
     *
     * @return  int
     */
    public function getATime();

    /**
     * Get inode change time of file.
     *
     * @return  int
     */
    public function getCTime();

    /**
     * Get file modification time.
     *
     * @return  int
     */
    public function getMTime();

    /**
     * Get file group.
     *
     * @return  int
     */
    public function getGroup();

    /**
     * Get file owner.
     *
     * @return  int
     */
    public function getOwner();

    /**
     * Get file permissions.
     *
     * @return  int
     */
    public function getPermissions();

    /**
     * Check if the file is readable.
     *
     * @return  bool
     */
    public function isReadable();

    /**
     * Check if the file is writable.
     *
     * @return  bool
     */
    public function isWritable();

    /**
     * Check if the file is executable.
     *
     * @return  bool
     */
    public function isExecutable();

    /**
     * Clear file status cache.
     *
     * @return  void
     */
    public function clearStatisticCache();

    /**
     * Clear all files status cache.
     *
     * @return  void
     */
    public static function clearAllStatisticCaches();
}
