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

namespace Hoa\Math\Combinatorics\Combination;

use Hoa\Iterator;

/**
 * Class \Hoa\Math\Combinatorics\Combination\Gamma.
 *
 * Gamma^n_k denotes the set of k-uples whose sum of elements is n. For example:
 * Gamma^2_3 = {(2, 0, 0), (1, 1, 0), (1, 0, 1), (0, 2, 0), (0, 1, 1), (0, 0, 2)}.
 * For any k-uple γ and any α in {1, …, k}, γ_α denotes the α-th element of γ.
 * This class is identical to \Hoa\Math\Combinatorics\Combination::Gamma with a
 * “yield” keyword.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Gamma implements Iterator
{
    /**
     * n.
     *
     * @var int
     */
    protected $_n       = 0;

    /**
     * k.
     *
     * @var int
     */
    protected $_k       = 0;

    /**
     * For iterator.
     *
     * @var int
     */
    protected $_current = null;

    /**
     * For iterator.
     *
     * @var int
     */
    protected $_key     = -1;

    /**
     * For iterator.
     *
     * @var array
     */
    protected $_tmp     = null;

    /**
     * For iterator.
     *
     * @var int
     */
    protected $_i       = 0;

    /**
     * For iterator.
     *
     * @var int
     */
    protected $_o       = 0;

    /**
     * For iterator.
     *
     *
     * @var bool
     */
    protected $_last    = false;



    /**
     * Constructor.
     *
     * @param   int  $n    n.
     * @param   int  $k    k.
     */
    public function __construct($n, $k)
    {
        $this->_n = $n;
        $this->_k = $k;

        return;
    }

    /**
     * Get current γ.
     *
     * @return  array
     */
    public function current()
    {
        return $this->_current;
    }

    /**
     * Get current α.
     *
     * @return  int
     */
    public function key()
    {
        return $this->_key;
    }

    /**
     * Compute γ_{α + 1}.
     *
     * @return  void
     */
    public function next()
    {
        return;
    }

    /**
     * Rewind iterator.
     *
     * @return  void
     */
    public function rewind()
    {
        $this->_current = [];
        $this->_tmp     = null;
        $this->_i       = 0;
        $this->_o       = 0 === $this->_n
                              ? [0]
                              : array_fill(0, $this->_n, 0);
        $this->_o[0]    = $this->_k;
        $this->_last    = false;

        return;
    }

    /**
     * Compute γ_α.
     *
     * @return  bool
     */
    public function valid()
    {
        if (true === $this->_last) {
            return false;
        }

        if (0 === $this->_n) {
            return false;
        }

        if ($this->_k == $this->_o[$this->_i = $this->_n - 1]) {
            $this->_last    = true;
            $this->_current = $this->_o;
            ++$this->_key;

            return true;
        }

        $this->_current = $this->_o;
        ++$this->_key;

        $this->_tmp          = $this->_o[$this->_i];
        $this->_o[$this->_i] = 0;

        while ($this->_o[$this->_i] == 0) {
            --$this->_i;
        }

        --$this->_o[$this->_i];
        $this->_o[$this->_i + 1] = $this->_tmp + 1;

        return true;
    }
}
