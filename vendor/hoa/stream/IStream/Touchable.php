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
 * Interface \Hoa\Stream\IStream\Touchable.
 *
 * Interface for touchable input/output.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
interface Touchable extends Stream
{
    /**
     * Overwrite file if already exists.
     *
     * @const bool
     */
    const OVERWRITE             = true;

    /**
     * Do not overwrite file if already exists.
     *
     * @const bool
     */
    const DO_NOT_OVERWRITE      = false;

    /**
     * Make directory if does not exist.
     *
     * @const bool
     */
    const MAKE_DIRECTORY        = true;

    /**
     * Do not make directory if does not exist.
     *
     * @const bool
     */
    const DO_NOT_MAKE_DIRECTORY = false;



    /**
     * Set access and modification time of file.
     *
     * @param   int     $time     Time. If equals to -1, time() should be used.
     * @param   int     $atime    Access time. If equals to -1, $time should be
     *                            used.
     * @return  bool
     */
    public function touch($time = -1, $atime = -1);

    /**
     * Copy file.
     * Return the destination file path if succeed, false otherwise.
     *
     * @param   string  $to       Destination path.
     * @param   bool    $force    Force to copy if the file $to already exists.
     *                            Use the self::*OVERWRITE constants.
     * @return  bool
     */
    public function copy($to, $force = self::DO_NOT_OVERWRITE);

    /**
     * Move a file.
     *
     * @param   string  $name     New name.
     * @param   bool    $force    Force to move if the file $name already
     *                            exists.
     *                            Use the self::*OVERWRITE constants.
     * @param   bool    $mkdir    Force to make directory if does not exist.
     *                            Use the self::*DIRECTORY constants.
     * @return  bool
     */
    public function move(
        $name,
        $force = self::DO_NOT_OVERWRITE,
        $mkdir = self::DO_NOT_MAKE_DIRECTORY
    );

    /**
     * Delete a file.
     *
     * @return  bool
     */
    public function delete();

    /**
     * Change file group.
     *
     * @param   mixed   $group    Group name or number.
     * @return  bool
     */
    public function changeGroup($group);

    /**
     * Change file mode.
     *
     * @param   int     $mode    Mode (in octal!).
     * @return  bool
     */
    public function changeMode($mode);

    /**
     * Change file owner.
     *
     * @param   mixed   $user    User.
     * @return  bool
     */
    public function changeOwner($user);

    /**
     * Change the current umask.
     *
     * @param   int     $umask    Umask (in octal!). If null, given the current
     *                            umask value.
     * @return  int
     */
    public static function umask($umask = null);
}
