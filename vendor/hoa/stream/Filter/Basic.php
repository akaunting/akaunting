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

namespace Hoa\Stream\Filter;

use Hoa\Stream;

/**
 * Class \Hoa\Stream\Filter\Basic.
 *
 * Basic filter. Force to implement some methods.
 * Actually, it extends the php_user_filter class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
abstract class Basic extends \php_user_filter implements Stream\IStream\Stream
{
    /**
     * Filter processed successfully with data available in the out bucket
     * brigade.
     *
     * @const int
     */
    const PASS_ON          = PSFS_PASS_ON;

    /**
     * Filter processed successfully, however no data was available to return.
     * More data is required from the stream or prior filter.
     *
     * @const int
     */
    const FEED_ME          = PSFS_FEED_ME;

    /**
     * The filter experienced and unrecoverable error and cannot continue.
     *
     * @const int
     */
    const FATAL_ERROR      = PSFS_ERR_FATAL;

    /**
     * Regular read/write.
     *
     * @const int
     */
    const FLAG_NORMAL      = PSFS_FLAG_NORMAL;

    /**
     * An incremental flush.
     *
     * @const int
     */
    const FLAG_FLUSH_INC   = PSFS_FLAG_FLUSH_INC;

    /**
     * Final flush prior to closing.
     *
     * @const int
     */
    const FLAG_FLUSH_CLOSE = PSFS_FLAG_FLUSH_CLOSE;



    /**
     * Filter data.
     * This method is called whenever data is read from or written to the attach
     * stream.
     *
     * @param   resource  $in           A resource pointing to a bucket brigade
     *                                  which contains one or more bucket
     *                                  objects containing data to be filtered.
     * @param   resource  $out          A resource pointing to a second bucket
     *                                  brigade into which your modified buckets
     *                                  should be replaced.
     * @param   int       &$consumed    Which must always be declared by
     *                                  reference, should be incremented by the
     *                                  length of the data which your filter
     *                                  reads in and alters.
     * @param   bool      $closing      If the stream is in the process of
     *                                  closing (and therefore this is the last
     *                                  pass through the filterchain), the
     *                                  closing parameter will be set to true.
     * @return  int
     */
    public function filter($in, $out, &$consumed, $closing)
    {
        $iBucket = new Stream\Bucket($in);
        $oBucket = new Stream\Bucket($out);

        while (false === $iBucket->eob()) {
            $consumed += $iBucket->getLength();
            $oBucket->append($iBucket);
        }

        unset($iBucket);
        unset($oBucket);

        return self::PASS_ON;
    }

    /**
     * Called during instanciation of the filter class object.
     *
     * @return  bool
     */
    public function onCreate()
    {
        return true;
    }

    /**
     * Called upon filter shutdown (typically, this is also during stream
     * shutdown), and is executed after the flush method is called.
     *
     * @return  void
     */
    public function onClose()
    {
        return;
    }

    /**
     * Set the filter name.
     *
     * @param   string  $name    Filter name.
     * @return  string
     */
    public function setName($name)
    {
        $old              = $this->filtername;
        $this->filtername = $name;

        return $old;
    }

    /**
     * Set the filter parameters.
     *
     * @param   mixed   $parameters    Filter parameters.
     * @return  mixed
     */
    public function setParameters($parameters)
    {
        $old          = $this->params;
        $this->params = $parameters;

        return $old;
    }

    /**
     * Get the filter name.
     *
     * @return  string
     */
    public function getName()
    {
        return $this->filtername;
    }

    /**
     * Get the filter parameters.
     *
     * @return  mixed
     */
    public function getParameters()
    {
        return $this->params;
    }

    /**
     * Get the stream resource being filtered.
     * Maybe available only during **filter** calls when the closing parameter
     * is set to false.
     *
     * @return  resource
     */
    public function getStream()
    {
        return isset($this->stream) ? $this->stream : null;
    }
}
