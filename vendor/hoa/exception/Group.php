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

namespace Hoa\Exception;

/**
 * Class \Hoa\Exception\Group.
 *
 * This is an exception that contains a group of exceptions.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Group extends Exception implements \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * All exceptions (stored in a stack for transactions).
     *
     * @var \SplStack
     */
    protected $_group = null;



    /**
     * Create an exception.
     *
     * @param   string      $message      Formatted message.
     * @param   int         $code         Code (the ID).
     * @param   array       $arguments    Arguments to format message.
     * @param   \Exception  $previous     Previous exception in chaining.
     */
    public function __construct(
        $message,
        $code                = 0,
        $arguments           = [],
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $arguments, $previous);
        $this->_group = new \SplStack();
        $this->beginTransaction();

        return;
    }

    /**
     * Raise an exception as a string.
     *
     * @param   bool    $previous    Whether raise previous exception if exists.
     * @return  string
     */
    public function raise($previous = false)
    {
        $out = parent::raise($previous);

        if (0 >= count($this)) {
            return $out;
        }

        $out .= "\n\n" . 'Contains the following exceptions:';

        foreach ($this as $exception) {
            $out .=
                "\n\n" . '  • ' .
                str_replace(
                    "\n",
                    "\n" . '    ',
                    $exception->raise($previous)
                );
        }

        return $out;
    }

    /**
     * Begin a transaction.
     *
     * @return  \Hoa\Exception\Group
     */
    public function beginTransaction()
    {
        $this->_group->push(new \ArrayObject());

        return $this;
    }

    /**
     * Rollback a transaction.
     *
     * @return  \Hoa\Exception\Group
     */
    public function rollbackTransaction()
    {
        if (1 >= count($this->_group)) {
            return $this;
        }

        $this->_group->pop();

        return $this;
    }

    /**
     * Commit a transaction.
     *
     * @return  \Hoa\Exception\Group
     */
    public function commitTransaction()
    {
        if (false === $this->hasUncommittedExceptions()) {
            $this->_group->pop();

            return $this;
        }

        foreach ($this->_group->pop() as $index => $exception) {
            $this[$index] = $exception;
        }

        return $this;
    }

    /**
     * Check if there is uncommitted exceptions.
     *
     * @return  bool
     */
    public function hasUncommittedExceptions()
    {
        return
            1 < count($this->_group) &&
            0 < count($this->_group->top());
    }

    /**
     * Check if an index in the group exists.
     *
     * @param   mixed  $index    Index.
     * @return  bool
     */
    public function offsetExists($index)
    {
        foreach ($this->_group as $group) {
            if (isset($group[$index])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get an exception from the group.
     *
     * @param   mixed  $index    Index.
     * @return  Exception
     */
    public function offsetGet($index)
    {
        foreach ($this->_group as $group) {
            if (isset($group[$index])) {
                return $group[$index];
            }
        }

        return null;
    }

    /**
     * Set an exception in the group.
     *
     * @param   mixed       $index        Index.
     * @param   Exception  $exception    Exception.
     * @return  void
     */
    public function offsetSet($index, $exception)
    {
        if (!($exception instanceof \Exception)) {
            return null;
        }

        $group = $this->_group->top();

        if (null === $index ||
            true === is_int($index)) {
            $group[] = $exception;
        } else {
            $group[$index] = $exception;
        }

        return;
    }

    /**
     * Remove an exception in the group.
     *
     * @param   mixed  $index    Index.
     * @return  void
     */
    public function offsetUnset($index)
    {
        foreach ($this->_group as $group) {
            if (isset($group[$index])) {
                unset($group[$index]);
            }
        }

        return;
    }

    /**
     * Get committed exceptions in the group.
     *
     * @return  \ArrayObject
     */
    public function getExceptions()
    {
        return $this->_group->bottom();
    }

    /**
     * Get an iterator over all exceptions (committed or not).
     *
     * @return  \ArrayIterator
     */
    public function getIterator()
    {
        return $this->getExceptions()->getIterator();
    }

    /**
     * Count the number of committed exceptions.
     *
     * @return  int
     */
    public function count()
    {
        return count($this->getExceptions());
    }

    /**
     * Count the stack size, i.e. the number of opened transactions.
     *
     * @return  int
     */
    public function getStackSize()
    {
        return count($this->_group);
    }
}
