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

namespace Hoa\Stream\Test\Unit\Filter;

use Hoa\Stream as LUT;
use Mock\Hoa\Stream\Filter\Basic as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Stream\Test\Unit\Filter\Basic.
 *
 * Test suite of the basic filter class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Basic extends Test\Unit\Suite
{
    public function case_constants()
    {
        $this
            ->integer(SUT::PASS_ON)
                ->isEqualTo(PSFS_PASS_ON)
            ->integer(SUT::FEED_ME)
                ->isEqualTo(PSFS_FEED_ME)
            ->integer(SUT::FATAL_ERROR)
                ->isEqualTo(PSFS_ERR_FATAL)
            ->integer(SUT::FLAG_NORMAL)
                ->isEqualTo(PSFS_FLAG_NORMAL)
            ->integer(SUT::FLAG_FLUSH_INC)
                ->isEqualTo(PSFS_FLAG_FLUSH_INC)
            ->integer(SUT::FLAG_FLUSH_CLOSE)
                ->isEqualTo(PSFS_FLAG_FLUSH_CLOSE);
    }

    public function case_is_a_php_filter()
    {
        $this
            ->when($result = new SUT())
            ->then
                ->object($result)
                    ->isInstanceOf(\php_user_filter::class);
    }

    public function case_interfaces()
    {
        $this
            ->when($result = new SUT())
            ->then
                ->object($result)
                    ->isInstanceOf(LUT\IStream\Stream::class);
    }

    public function case_set_name()
    {
        $this
            ->given($filter = new SUT())
            ->when($result = $filter->setName('foo'))
            ->then
                ->string($result)
                    ->isEqualTo('');
    }

    public function case_get_name()
    {
        $this
            ->given(
                $filter = new SUT(),
                $name   = 'foo',
                $filter->setName($name)
            )
            ->when($result = $filter->getName())
            ->then
                ->string($result)
                    ->isEqualTo($name);
    }

    public function case_set_parameters()
    {
        $this
            ->given($filter = new SUT())
            ->when($result = $filter->setParameters(['foo', 'bar', 'baz']))
            ->then
                ->string($result)
                    ->isEqualTo('');
    }

    public function case_get_parameters()
    {
        $this
            ->given(
                $filter     = new SUT(),
                $parameters = ['foo', 'bar', 'baz'],
                $filter->setParameters($parameters)
            )
            ->when($result = $filter->getParameters())
            ->then
                ->array($result)
                    ->isEqualTo($parameters);
    }

    public function case_get_stream()
    {
        $this
            ->given($filter = new SUT())
            ->when($result = $filter->getStream())
            ->then
                ->variable($result)
                    ->isNull(); // Only available when filtering, so `null` is valid.
    }
}
