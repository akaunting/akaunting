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

namespace Hoa\Stream\Wrapper\IWrapper;

/**
 * Interface \Hoa\Stream\Wrapper\IWrapper\Stream.
 *
 * Interface for “stream stream wrapper” class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license     New BSD License
 */
interface Stream
{
    /**
     * Retrieve the underlaying resource.
     *
     * @param   int     $castAs    Can be STREAM_CAST_FOR_SELECT when
     *                             stream_select() is calling stream_cast() or
     *                             STREAM_CAST_AS_STREAM when stream_cast() is
     *                             called for other uses.
     * @return  resource
     */
    public function stream_cast($castAs);

    /**
     * Close a resource.
     * This method is called in response to fclose().
     * All resources that were locked, or allocated, by the wrapper should be
     * released.
     *
     * @return  void
     */
    public function stream_close();

    /**
     * Tests for end-of-file on a file pointer.
     * This method is called in response to feof().
     *
     * @return  bool
     */
    public function stream_eof();

    /**
     * Flush the output.
     * This method is called in response to fflush().
     * If we have cached data in our stream but not yet stored it into the
     * underlying storage, we should do so now.
     *
     * @return  bool
     */
    public function stream_flush();

    /**
     * Advisory file locking.
     * This method is called in response to flock(), when file_put_contents()
     * (when flags contains LOCK_EX), stream_set_blocking() and when closing the
     * stream (LOCK_UN).
     *
     * @param   int     $operation    Operation is one the following:
     *                                  * LOCK_SH to acquire a shared lock (reader) ;
     *                                  * LOCK_EX to acquire an exclusive lock (writer) ;
     *                                  * LOCK_UN to release a lock (shared or exclusive) ;
     *                                  * LOCK_NB if we don't want flock() to
     *                                    block while locking (not supported on
     *                                    Windows).
     * @return  bool
     */
    public function stream_lock($operation);

    /**
     * Change stream options.
     * This method is called to set metadata on the stream. It is called when
     * one of the following functions is called one a stream URL: touch, chmod,
     * chown or chgrp.
     *
     * @param   string  $path      The file path or URL to set metadata.
     * @param   int     $option    One of the following:
     *                               * STREAM_META_TOUCH,
     *                               * STREAM_META_OWNER_NAME,
     *                               * STREAM_META_OWNER,
     *                               * STREAM_META_GROUP_NAME,
     *                               * STREAM_META_GROUP,
     *                               * STREAM_META_ACCESS.
     * @param   mixed   $value     An array or a scalar depending of the option.
     * @return  bool
     */
    public function stream_metadata($path, $option, $value);

    /**
     * Open file or URL.
     * This method is called immediately after the wrapper is initialized (f.e.
     * by fopen() and file_get_contents()).
     *
     * @param   string  $path           Specifies the URL that was passed to the
     *                                  original function.
     * @param   string  $mode           The mode used to open the file, as
     *                                  detailed for fopen().
     * @param   int     $options        Holds additional flags set by the
     *                                  streams API. It can hold one or more of
     *                                  the following values OR'd together:
     *                                    * STREAM_USE_PATH, if path is relative,
     *                                      search for the resource using the
     *                                      include_path;
     *                                    * STREAM_REPORT_ERRORS, if this is
     *                                    set, you are responsible for raising
     *                                    errors using trigger_error during
     *                                    opening the stream. If this is not
     *                                    set, you should not raise any errors.
     * @param   string  &$openedPath    If the $path is opened successfully, and
     *                                  STREAM_USE_PATH is set in $options,
     *                                  $openedPath should be set to the full
     *                                  path of the file/resource that was
     *                                  actually opened.
     * @return  bool
     */
    public function stream_open($path, $mode, $options, &$openedPath);

    /**
     * Read from stream.
     * This method is called in response to fread() and fgets().
     *
     * @param   int     $count    How many bytes of data from the current
     *                            position should be returned.
     * @return  string
     */
    public function stream_read($count);

    /**
     * Seek to specific location in a stream.
     * This method is called in response to fseek().
     * The read/write position of the stream should be updated according to the
     * $offset and $whence.
     *
     * @param   int     $offset    The stream offset to seek to.
     * @param   int     $whence    Possible values:
     *                               * SEEK_SET to set position equal to $offset
     *                                 bytes ;
     *                               * SEEK_CUR to set position to current
     *                                 location plus $offsete ;
     *                               * SEEK_END to set position to end-of-file
     *                                 plus $offset.
     * @return  bool
     */
    public function stream_seek($offset, $whence = SEEK_SET);

    /**
     * Change stream options.
     * This method is called to set options on the stream.
     *
     * @param   int     $option    One of:
     *                               * STREAM_OPTION_BLOCKING, the method was
     *                                 called in response to
     *                                 stream_set_blocking() ;
     *                               * STREAM_OPTION_READ_TIMEOUT, the method
     *                                 was called in response to
     *                                 stream_set_timeout() ;
     *                               * STREAM_OPTION_WRITE_BUFFER, the method
     *                                 was called in response to
     *                                 stream_set_write_buffer().
     * @param   int     $arg1      If $option is:
     *                               * STREAM_OPTION_BLOCKING: requested blocking
     *                                 mode (1 meaning block, 0 not blocking) ;
     *                               * STREAM_OPTION_READ_TIMEOUT: the timeout
     *                                 in seconds ;
     *                               * STREAM_OPTION_WRITE_BUFFER: buffer mode
     *                                 (STREAM_BUFFER_NONE or
     *                                 STREAM_BUFFER_FULL).
     * @param   int     $arg2      If $option is:
     *                               * STREAM_OPTION_BLOCKING: this option is
     *                                 not set ;
     *                               * STREAM_OPTION_READ_TIMEOUT: the timeout
     *                                 in microseconds ;
     *                               * STREAM_OPTION_WRITE_BUFFER: the requested
     *                                 buffer size.
     * @return  bool
     */
    public function stream_set_option($option, $arg1, $arg2);

    /**
     * Retrieve information about a file resource.
     * This method is called in response to fstat().
     *
     * @return  array
     */
    public function stream_stat();

    /**
     * Retrieve the current position of a stream.
     * This method is called in response to ftell().
     *
     * @return  int
     */
    public function stream_tell();

    /**
     * Truncate a stream to a given length.
     *
     * @param   int     $size    Size.
     * @return  bool
     */
    public function stream_truncate($size);

    /**
     * Write to stream.
     * This method is called in response to fwrite().
     *
     * @param   string  $data    Should be stored into the underlying stream.
     * @return  int
     */
    public function stream_write($data);
}
