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
 * Class \Hoa\Iterator\Repeater.
 *
 * Repeat an iterator n-times.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Repeater implements Iterator
{
    /**
     * Current iterator.
     *
     * @var \Traversable
     */
    protected $_iterator = null;

    /**
     * Maximum repetition.
     *
     * @var int
     */
    protected $_n        = 1;

    /**
     * Current repetition.
     *
     * @var int
     */
    protected $_i        = 1;

    /**
     * Body (callable to execute each time).
     *
     * @var callable
     */
    protected $_body     = null;



    /**
     * Constructor.
     *
     * @param   \Traversable  $iterator    Iterator.
     * @param   int           $n           Repeat $n-times.
     * @param   callable      $body        Body.
     * @throws  \Hoa\Iterator\Exception
     */
    public function __construct(\Traversable $iterator, $n, $body = null)
    {
        if (0 >= $n) {
            throw new Exception(
                'n must be greater than 0, given %d.',
                0,
                $n
            );
        }

        if ($iterator instanceof \IteratorAggregate) {
            $iterator = $iterator->getIterator();
        }

        $this->_iterator = $iterator;
        $this->_n        = $n;
        $this->_body     = $body;

        return;
    }

    /**
     * Return the current element.
     *
     * @return  mixed
     */
    public function current()
    {
        return $this->_iterator->current();
    }

    /**
     * Return the key of the current element.
     *
     * @return  mixed
     */
    public function key()
    {
        return $this->_iterator->key();
    }

    /**
     * Move forward to next element.
     *
     * @return  void
     */
    public function next()
    {
        return $this->_iterator->next();
    }

    /**
     * Rewind the iterator to the first element.
     *
     * @return  void
     */
    public function rewind()
    {
        return $this->_iterator->rewind();
    }

    /**
     * Check if current position is valid.
     *
     * @return  bool
     */
    public function valid()
    {
        $valid = $this->_iterator->valid();

        if (true === $valid) {
            return true;
        }

        if (null !== $this->_body) {
            $handle = &$this->_body;
            $handle($this->_i);
        }

        $this->rewind();

        if ($this->_n <= $this->_i++) {
            $this->_i = 1;

            return false;
        }

        return true;
    }
}
