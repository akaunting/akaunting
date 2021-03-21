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

namespace Hoa\Stream;

/**
 * Class \Hoa\Stream\Bucket.
 *
 * Manipulate stream buckets through brigades.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Bucket
{
    /**
     * Whether the stream is already a brigade.
     *
     * @const bool
     */
    const IS_A_BRIGADE = true;

    /**
     * Whether the stream is not a brigade.
     *
     * @const bool
     */
    const IS_A_STREAM  = false;

    /**
     * Type of the bucket.
     *
     * @var bool
     */
    protected $_type    = null;

    /**
     * Brigade.
     *
     * @var resource
     */
    protected $_brigade = null;

    /**
     * Bucket.
     *
     * @var resource
     */
    protected $_bucket  = null;



    /**
     * Set a brigade.
     * If a stream is given (with the constant `self::IS_A_STREAM`), it will
     * create a brigade automatically.
     *
     * @param   resource  &$brigade    A stream or a brigade.
     * @param   bool      $is          Specify if `$brigade` is a stream or a
     *                                 brigade, given by `self::IS_A_*` constant.
     * @param   string    $buffer      Stream buffer.
     */
    public function __construct(&$brigade, $is = self::IS_A_BRIGADE, $buffer = '')
    {
        $this->setType($is);

        if (self::IS_A_BRIGADE === $this->getType()) {
            $this->setBrigade($brigade);
        } else {
            $this->setBucket(stream_bucket_new($brigade, $buffer));
            $bucket = $this->getBucket();
            $this->setBrigade($bucket);
        }

        return;
    }

    /**
     * Test the end-of-bucket.
     * When testing, set the new bucket object.
     *
     * @return  bool
     */
    public function eob()
    {
        $this->_bucket = null;

        return false == $this->getBucket();
    }

    /**
     * Append bucket to the brigade.
     *
     * @param   \Hoa\Stream\Bucket  $bucket    Bucket to add.
     * @return  void
     */
    public function append(Bucket $bucket)
    {
        stream_bucket_append($this->getBrigade(), $bucket->getBucket());

        return;
    }

    /**
     * Prepend bucket to the brigade.
     *
     * @param   \Hoa\Stream\Bucket  $bucket    Bucket to add.
     * @return  void
     */
    public function prepend(Bucket $bucket)
    {
        stream_bucket_prepend($this->getBrigade(), $bucket->getBucket());

        return;
    }

    /**
     * Set type.
     *
     * @param   bool  $type    Type. Please, see self::IS_A_* constants.
     * @return  bool
     */
    protected function setType($type)
    {
        $old         = $this->_type;
        $this->_type = $type;

        return $old;
    }

    /**
     * Get type.
     *
     * @return  bool
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Set bucket data.
     *
     * @param   string  $data    Data to set.
     * @return  string
     */
    public function setData($data)
    {
        $old                        = $this->getBucket()->data;
        $this->getBucket()->data    = $data;
        $this->getBucket()->datalen = strlen($this->getBucket()->data);

        return $old;
    }

    /**
     * Get bucket data.
     *
     * @return  string
     */
    public function getData()
    {
        if (null === $this->getBucket()) {
            return null;
        }

        return $this->getBucket()->data;
    }

    /**
     * Get bucket length.
     *
     * @return  int
     */
    public function getLength()
    {
        if (null === $this->getBucket()) {
            return 0;
        }

        return $this->getBucket()->datalen;
    }

    /**
     * Set the brigade.
     *
     * @param   resource   &$brigade    Brigade to add.
     * @return  resource
     */
    protected function setBrigade(&$brigade)
    {
        $old            = $this->_brigade;
        $this->_brigade = $brigade;

        return $old;
    }

    /**
     * Get the brigade.
     *
     * @return  resource
     */
    public function getBrigade()
    {
        return $this->_brigade;
    }

    /**
     * Set bucket.
     *
     * @param   resource  $bucket    Bucket.
     * @return  resource
     */
    protected function setBucket($bucket)
    {
        $old           = $this->_bucket;
        $this->_bucket = $bucket;

        return $old;
    }

    /**
     * Get the current bucket.
     *
     * @return  mixed
     */
    protected function getBucket()
    {
        if (null === $this->_bucket && self::IS_A_BRIGADE === $this->getType()) {
            $this->_bucket = stream_bucket_make_writeable($this->getBrigade());
        }

        return $this->_bucket;
    }
}
