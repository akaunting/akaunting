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

namespace Hoa\Iterator;

/**
 * Class \Hoa\Iterator\SplFileInfo.
 *
 * Enhance SplFileInfo implementation.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class SplFileInfo extends \SplFileInfo
{
    /**
     * Hash.
     *
     * @var string
     */
    protected $_hash         = null;

    /**
     * Relative path.
     *
     * @var string
     */
    protected $_relativePath = null;



    /**
     * Construct.
     *
     * @param   string  $filename        Filename.
     * @param   string  $relativePath    Relative path.
     */
    public function __construct($filename, $relativePath = null)
    {
        parent::__construct($filename);

        if (-1 !== $mtime = $this->getMTime()) {
            $this->_hash = md5($this->getPathname() . $mtime);
        }

        $this->_relativePath = $relativePath;

        return;
    }

    /**
     * Get the hash.
     *
     * @return  string
     */
    public function getHash()
    {
        return $this->_hash;
    }

    /**
     * Get the MTime.
     *
     * @return  int
     */
    public function getMTime()
    {
        try {
            return parent::getMTime();
        } catch (\RuntimeException $e) {
            return -1;
        }
    }

    /**
     * Set relative path.
     *
     * @param   string  $relativePath    Relative path.
     * @return  string
     */
    public function setRelativePath($relativePath)
    {
        $old                 = $this->_relativePath;
        $this->_relativePath = $relativePath;

        return $old;
    }

    /**
     * Get relative path (if given).
     *
     * @return  string
     */
    public function getRelativePath()
    {
        return $this->_relativePath;
    }

    /**
     * Get relative pathname (if possible).
     *
     * @return  string
     */
    public function getRelativePathname()
    {
        if (null === $relative = $this->getRelativePath()) {
            return $this->getPathname();
        }

        return substr($this->getPathname(), strlen($relative));
    }
}
