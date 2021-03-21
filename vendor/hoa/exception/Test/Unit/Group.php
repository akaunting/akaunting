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

namespace Hoa\Exception\Test\Unit;

use Hoa\Exception\Group as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Exception\Test\Unit\Group.
 *
 * Test suite of the group class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Group extends Test\Unit\Suite
{
    public function case_is_an_exception_arrayaccess_iteratoraggregate_countable()
    {
        $this
            ->when($result = new SUT('foo'))
            ->then
                ->object($result)
                    ->isInstanceOf('Hoa\Exception\Exception')
                    ->isInstanceOf('ArrayAccess')
                    ->isInstanceOf('IteratorAggregate')
                    ->isInstanceOf('Countable');
    }

    public function case_constructor()
    {
        $this
            ->given(
                $message   = 'foo %s %d %s',
                $code      = 7,
                $arguments = ['arg', 42, null],
                $previous  = new SUT('previous')
            )
            ->when($result = new SUT($message, $code, $arguments, $previous), $line = __LINE__)
            ->then
                ->string($result->getMessage())
                    ->isEqualTo('foo arg 42 (null)')
                ->integer($result->getCode())
                    ->isEqualTo(7)
                ->array($result->getArguments())
                    ->isEqualTo(['arg', 42, '(null)'])
                ->object($result->getPreviousThrow())
                    ->isIdenticalTo($previous)
                ->boolean($result->hasUncommittedExceptions())
                    ->isFalse();
    }

    public function case_raise_zero_exception()
    {
        $this
            ->given($group = new SUT('foo'), $line = __LINE__)
            ->when($result = $group->raise())
            ->then
                ->string($result)
                    ->isEqualTo(
                        __METHOD__ . '(): (0) foo' . "\n" .
                        'in ' . __FILE__ . ' at line ' . $line . '.'
                    );
    }

    public function case_raise_one_exception()
    {
        $this
            ->given(
                $exception1 = new SUT('bar'), $barLine = __LINE__,
                $group      = new SUT('foo'), $fooLine = __LINE__,
                $group[]    = $exception1
            )
            ->when($result = $group->raise())
            ->then
                ->string($result)
                    ->isEqualTo(
                        __METHOD__ . '(): (0) foo' . "\n" .
                        'in ' . __FILE__ . ' at line ' . $fooLine . '.' . "\n\n" .
                        'Contains the following exceptions:' . "\n\n" .
                        '  • ' . __METHOD__ . '(): (0) bar' . "\n" .
                        '    in ' . __FILE__ . ' at line ' . $barLine . '.'
                    );
    }

    public function case_raise_more_exceptions()
    {
        $this
            ->given(
                $exception1 = new SUT('bar'), $barLine = __LINE__,
                $exception2 = new SUT('baz'), $bazLine = __LINE__,
                $group      = new SUT('foo'), $fooLine = __LINE__,
                $group[]    = $exception1,
                $group[]    = $exception2
            )
            ->when($result = $group->raise())
            ->then
                ->string($result)
                    ->isEqualTo(
                        __METHOD__ . '(): (0) foo' . "\n" .
                        'in ' . __FILE__ . ' at line ' . $fooLine . '.' . "\n\n" .
                        'Contains the following exceptions:' . "\n\n" .
                        '  • ' . __METHOD__ . '(): (0) bar' . "\n" .
                        '    in ' . __FILE__ . ' at line ' . $barLine . '.' . "\n\n" .
                        '  • ' . __METHOD__ . '(): (0) baz' . "\n" .
                        '    in ' . __FILE__ . ' at line ' . $bazLine . '.'
                    );
    }

    public function case_begin_transaction()
    {
        $this
            ->given(
                $group = new SUT('foo'),
                $oldStackSize = $group->getStackSize()
            )
            ->when(
                $result    = $group->beginTransaction(),
                $stackSize = $group->getStackSize()
            )
            ->then
                ->integer($oldStackSize)
                    ->isEqualTo(1)
                ->object($result)
                    ->isIdenticalTo($group)
                ->integer($stackSize)
                    ->isEqualTo($oldStackSize + 1);
    }

    public function case_rollback_transaction_with_an_empty_stack()
    {
        $this
            ->given(
                $group = new SUT('foo'),
                $oldStackSize = $group->getStackSize()
            )
            ->when(
                $result    = $group->rollbackTransaction(),
                $stackSize = $group->getStackSize()
            )
            ->then
                ->integer($oldStackSize)
                    ->isEqualTo(1)
                ->object($result)
                    ->isIdenticalTo($group)
                ->integer($stackSize)
                    ->isEqualTo($oldStackSize);
    }

    public function case_rollback_transaction()
    {
        $this
            ->given(
                $group = new SUT('foo'),
                $group->beginTransaction(),
                $group->beginTransaction(),
                $oldStackSize = $group->getStackSize(),
                $group->rollbackTransaction()
            )
            ->when(
                $result    = $group->rollbackTransaction(),
                $stackSize = $group->getStackSize()
            )
            ->then
                ->integer($oldStackSize)
                    ->isEqualTo(3)
                ->object($result)
                    ->isIdenticalTo($group)
                ->integer($stackSize)
                    ->isEqualTo($oldStackSize - 2);
    }

    public function case_commit_transaction_with_an_empty_stack()
    {
        $this
            ->given(
                $group = new SUT('foo'),
                $group->beginTransaction(),
                $oldCount     = count($group),
                $oldStackSize = $group->getStackSize()
            )
            ->when(
                $result    = $group->commitTransaction(),
                $count     = count($group),
                $stackSize = $group->getStackSize()
            )
            ->then
                ->integer($oldCount)
                    ->isEqualTo(0)
                ->integer($oldStackSize)
                    ->isEqualTo(2)
                ->object($result)
                    ->isIdenticalTo($group)
                ->integer($count)
                    ->isEqualTo($oldCount)
                ->integer($stackSize)
                    ->isEqualTo($oldStackSize - 1);
    }

    public function case_commit_transaction()
    {
        $this
            ->given(
                $group = new SUT('foo'),
                $group->beginTransaction(),
                $exception1   = new SUT('bar'),
                $exception2   = new SUT('baz'),
                $group[]      = $exception1,
                $group[]      = $exception2,
                $oldCount     = count($group),
                $oldStackSize = $group->getStackSize()
            )
            ->when(
                $result    = $group->commitTransaction(),
                $count     = count($group),
                $stackSize = $group->getStackSize()
            )
            ->then
                ->integer($oldCount)
                    ->isEqualTo(0)
                ->integer($oldStackSize)
                    ->isEqualTo(2)
                ->object($result)
                    ->isIdenticalTo($group)
                ->integer($count)
                    ->isEqualTo($oldCount + 2)
                ->integer($stackSize)
                    ->isEqualTo($oldStackSize - 1)
                ->array(iterator_to_array($group->getIterator()))
                    ->isEqualTo([
                        0 => $exception1,
                        1 => $exception2
                    ]);
    }

    public function case_has_uncommitted_exceptions()
    {
        $this
            ->given(
                $group = new SUT('foo'),
                $group->beginTransaction(),
                $group[] = new SUT('bar')
            )
            ->when($result = $group->hasUncommittedExceptions())
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_has_no_uncommitted_exceptions()
    {
        $this
            ->given(
                $group = new SUT('foo'),
                $group->beginTransaction()
            )
            ->when($result = $group->hasUncommittedExceptions())
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_has_no_uncommitted_exceptions_with_empty_stack()
    {
        $this
            ->given(
                $group   = new SUT('foo'),
                $group[] = new SUT('bar')
            )
            ->when($result = $group->hasUncommittedExceptions())
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_offset_exists_with_no_uncommited_exceptions()
    {
        $this
            ->given(
                $group        = new SUT('foo'),
                $group['bar'] = new SUT('bar')
            )
            ->when($result = $group->offsetExists('bar'))
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_offset_does_not_exist_with_no_uncommited_exceptions()
    {
        $this
            ->given(
                $group        = new SUT('foo'),
                $group['bar'] = new SUT('bar')
            )
            ->when($result = $group->offsetExists('baz'))
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_offset_exists()
    {
        $this
            ->given(
                $group = new SUT('foo'),
                $group->beginTransaction(),
                $group->beginTransaction(),
                $group['bar'] = new SUT('bar')
            )
            ->when($result = $group->offsetExists('bar'))
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_offset_does_not_exist()
    {
        $this
            ->given(
                $group = new SUT('foo'),
                $group->beginTransaction(),
                $group->beginTransaction(),
                $group['bar'] = new SUT('bar')
            )
            ->when($result = $group->offsetExists('baz'))
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_offset_get_with_no_uncommited_exceptions()
    {
        $this
            ->given(
                $group        = new SUT('foo'),
                $exception1   = new SUT('bar'),
                $group['bar'] = $exception1
            )
            ->when($result = $group->offsetGet('bar'))
            ->then
                ->object($result)
                    ->isIdenticalTo($exception1);
    }

    public function case_offset_get_does_not_exist_with_no_uncommited_exceptions()
    {
        $this
            ->given(
                $group        = new SUT('foo'),
                $exception1   = new SUT('bar'),
                $group['bar'] = $exception1
            )
            ->when($result = $group->offsetGet('baz'))
            ->then
                ->variable($result)
                    ->isNull();
    }

    public function case_offset_get()
    {
        $this
            ->given(
                $group = new SUT('foo'),
                $group->beginTransaction(),
                $group->beginTransaction(),
                $exception1   = new SUT('bar'),
                $group['bar'] = $exception1
            )
            ->when($result = $group->offsetGet('bar'))
            ->then
                ->object($result)
                    ->isIdenticalTo($exception1);
    }

    public function case_offset_get_does_not_exist()
    {
        $this
            ->given(
                $group = new SUT('foo'),
                $group->beginTransaction(),
                $group->beginTransaction(),
                $exception1   = new SUT('bar'),
                $group['bar'] = $exception1
            )
            ->when($result = $group->offsetGet('baz'))
            ->then
                ->variable($result)
                    ->isNull();
    }

    public function case_offset_set_not_an_exception()
    {
        $this
            ->given($group = new SUT('foo'))
            ->when($group->offsetSet('bar', new \StdClass()))
            ->then
                ->boolean($group->offsetExists('bar'))
                    ->isFalse();
    }

    public function case_offset_set()
    {
        $this
            ->given(
                $group      = new SUT('foo'),
                $exception1 = new SUT('bar')
            )
            ->when($result = $group->offsetExists('bar'))
            ->then
                ->boolean($result)
                    ->isFalse()

            ->when($group->offsetSet('bar', $exception1))
            ->then
                ->boolean($group->offsetExists('bar'))
                    ->isTrue()
                ->object($group->offsetGet('bar'))
                    ->isIdenticalTo($exception1);
    }

    public function case_offset_set_with_a_null_index()
    {
        $this
            ->given(
                $group      = new SUT('foo'),
                $exception1 = new SUT('bar')
            )
            ->when($group->offsetSet(null, $exception1))
            ->then
                ->boolean($group->offsetExists(0))
                    ->isTrue()
                ->object($group->offsetGet(0))
                    ->isIdenticalTo($exception1);
    }

    public function case_offset_set_with_an_integer_index()
    {
        $this
            ->given(
                $group      = new SUT('foo'),
                $exception1 = new SUT('bar')
            )
            ->when($group->offsetSet(42, $exception1))
            ->then
                ->boolean($group->offsetExists(42))
                    ->isFalse()
                ->boolean($group->offsetExists(0))
                    ->isTrue()
                ->object($group->offsetGet(0))
                    ->isIdenticalTo($exception1);
    }

    public function case_offset_unset_with_no_uncommited_exceptions()
    {
        $this
            ->given(
                $group        = new SUT('foo'),
                $group['bar'] = new SUT('bar')
            )
            ->when($group->offsetUnset('bar'))
            ->then
                ->boolean($group->offsetExists('bar'))
                    ->isFalse();
    }

    public function case_offset_unset_does_not_exist_with_no_uncommited_exceptions()
    {
        $this
            ->given($group = new SUT('foo'))
            ->when($group->offsetUnset('bar'))
            ->then
                ->boolean($group->offsetExists('bar'))
                    ->isFalse();
    }

    public function case_offset_unset()
    {
        $this
            ->given(
                $group = new SUT('foo'),
                $group->beginTransaction(),
                $group->beginTransaction(),
                $group['bar'] = new SUT('bar')
            )
            ->when($result = $group->offsetUnset('bar'))
            ->then
                ->boolean($group->offsetExists('bar'))
                    ->isFalse();
    }

    public function case_offset_unset_does_not_exist()
    {
        $this
            ->given(
                $group = new SUT('foo'),
                $group->beginTransaction(),
                $group->beginTransaction()
            )
            ->when($result = $group->offsetUnset('bar'))
            ->then
                ->boolean($group->offsetExists('bar'))
                    ->isFalse();
    }

    public function case_get_exceptions()
    {
        $this
            ->given(
                $group        = new SUT('foo'),
                $exception1   = new SUT('bar'),
                $exception2   = new SUT('baz'),
                $group['bar'] = $exception1,
                $group->beginTransaction(),
                $group['baz'] = $exception2
            )
            ->when($result = $group->getExceptions())
            ->then
                ->object($result)
                    ->isInstanceOf('ArrayObject')
                ->object($result['bar'])
                    ->isIdenticalTo($exception1);
    }

    public function case_get_iterator()
    {
        $this
            ->given(
                $group        = new SUT('foo'),
                $exception1   = new SUT('bar'),
                $group['bar'] = $exception1
            )
            ->when($result = $group->getIterator())
            ->then
                ->object($result)
                    ->isInstanceOf('ArrayIterator')
                ->array(iterator_to_array($result))
                    ->isEqualTo([
                        'bar' => $exception1
                    ]);
    }

    public function case_count()
    {
        $this
            ->given(
                $group        = new SUT('foo'),
                $exception1   = new SUT('bar'),
                $exception2   = new SUT('baz'),
                $group['bar'] = $exception1,
                $group->beginTransaction(),
                $group['baz'] = $exception2
            )
            ->when($result = count($group))
            ->then
                ->integer($result)
                    ->isEqualTo(1);
    }

    public function get_get_stack_size()
    {
        $this
            ->given(
                $group        = new SUT('foo'),
                $exception1   = new SUT('bar'),
                $exception2   = new SUT('baz'),
                $group['bar'] = $exception1,
                $group->beginTransaction(),
                $group['baz'] = $exception2
            )
            ->when($result = $group->getStackSize())
            ->then
                ->integer($result)
                    ->isEqualTo(2);
    }
}
