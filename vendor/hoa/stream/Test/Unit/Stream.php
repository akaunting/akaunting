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

namespace Hoa\Stream\Test\Unit;

use Hoa\Event;
use Hoa\Stream as LUT;
use Hoa\Test;

/**
 * Class \Hoa\Stream\Test\Unit\Stream.
 *
 * Test suite of the stream class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Stream extends Test\Unit\Suite
{
    public function case_interfaces()
    {
        $this
            ->when($result = new SUT(__FILE__))
            ->then
                ->object($result)
                    ->isInstanceOf(LUT\IStream\Stream::class)
                    ->isInstanceOf(Event\Listenable::class);
    }

    public function case_constants()
    {
        $this
            ->integer(SUT::NAME)
                ->isEqualTo(0)
            ->integer(SUT::HANDLER)
                ->isEqualTo(1)
            ->integer(SUT::RESOURCE)
                ->isEqualTo(2)
            ->integer(SUT::CONTEXT)
                ->isEqualTo(3);
    }

    public function case_construct()
    {
        $this
            ->given($name = __FILE__)
            ->when($result = new SUT($name))
            ->then
                ->string($result->getStreamName())
                    ->isEqualTo($name)
                ->boolean($this->invoke($result)->hasBeenDeferred())
                    ->isFalse()
                ->let($listener = $this->invoke($result)->getListener())
                ->object($listener)
                    ->isInstanceOf(Event\Listener::class)
                ->boolean($listener->listenerExists('authrequire'))
                    ->isTrue()
                ->boolean($listener->listenerExists('authresult'))
                    ->isTrue()
                ->boolean($listener->listenerExists('complete'))
                    ->isTrue()
                ->boolean($listener->listenerExists('connect'))
                    ->isTrue()
                ->boolean($listener->listenerExists('failure'))
                    ->isTrue()
                ->boolean($listener->listenerExists('mimetype'))
                    ->isTrue()
                ->boolean($listener->listenerExists('progress'))
                    ->isTrue()
                ->boolean($listener->listenerExists('redirect'))
                    ->isTrue()
                ->boolean($listener->listenerExists('resolve'))
                    ->isTrue()
                ->boolean($listener->listenerExists('size'))
                    ->isTrue()
                ->boolean(Event::eventExists('hoa://Event/Stream/' . $name))
                    ->isTrue()
                ->boolean(Event::eventExists('hoa://Event/Stream/' . $name . ':close-before'))
                    ->isTrue();
    }

    public function case_construct_with_a_context()
    {
        $this
            ->given(
                $name        = __FILE__,
                $contextName = 'foo',
                LUT\Context::getInstance($contextName)
            )
            ->when($result = new SUT($name, $contextName))
            ->then
                ->string($result->getStreamName())
                    ->isEqualTo($name)
                ->boolean($this->invoke($result)->hasBeenDeferred())
                    ->isFalse()
                ->object($this->invoke($result)->getListener())
                    ->isInstanceOf(Event\Listener::class);
    }

    public function case_construct_with_deferred_opening()
    {
        $this
            ->given($name = __FILE__)
            ->when($result = new SUT($name, null, true))
            ->then
                ->boolean($this->invoke($result)->hasBeenDeferred())
                    ->isTrue()
                ->boolean($result->isOpened())
                    ->isFalse()
                ->variable($result->getStreamName())
                    ->isNull();
    }

    public function case_open()
    {
        $this
            ->given(
                $name   = __FILE__,
                $stream = new SUT($name, null, true)
            )
            ->when($result = $stream->open())
            ->then
                ->object($result)
                    ->isIdenticalTo($stream)
                ->boolean($this->invoke($result)->hasBeenDeferred())
                    ->isTrue()
                ->boolean($result->isOpened())
                    ->isTrue()
                ->string($result->getStreamName())
                    ->isEqualTo($name)
                ->integer($result->getStreamBufferSize())
                    ->isEqualTo(SUT::DEFAULT_BUFFER_SIZE);
    }

    public function case_close()
    {
        $this
            ->given(
                $name     = __FILE__,
                $stream   = new SUT($name),
                $resource = $stream->getStream(),
                $context  = $stream->getStreamContext()
            )
            ->when($result = $stream->close())
            ->then
                ->variable($result)
                    ->isNull()
                ->boolean($stream->isOpened())
                    ->isFalse()
                ->variable(SUT::getStreamHandler($stream))
                    ->isNull()
                ->variable($stream->getStreamName())
                    ->isEqualTo($name)
                ->variable($stream->getStream())
                    ->isEqualTo($resource)
                ->variable($stream->getStreamContext())
                    ->isEqualTo($context)
                ->boolean(Event::eventExists('hoa://Event/Stream/' . $name))
                    ->isFalse()
                ->boolean(Event::eventExists('hoa://Event/Stream/' . $name . ':close-before'))
                    ->isFalse();
    }

    public function case_close_more_than_once()
    {
        $this
            ->given(
                $name   = __FILE__,
                $stream = new SUT($name),
                $close1 = $stream->close()
            )
            ->when($result = $stream->close())
            ->then
                ->variable($result)
                    ->isIdenticalTo($close1);
    }

    public function case_open_close_open()
    {
        $this
            ->given(
                $name   = __FILE__,
                $stream = new SUT($name, null, true),
                $stream->open(),
                $resource   = $stream->getStream(),
                $context    = $stream->getStreamContext(),
                $handler    = SUT::getStreamHandler($stream),

                $this->function->stream_set_write_buffer = 0,

                $stream->setStreamBuffer(42),
                $stream->close()
            )
            ->when($result = $stream->open())
            ->then
                ->string($result->getStreamName())
                    ->isEqualTo($name)
                ->resource($result->getStream())
                    ->isNotEqualTo($resource)
                ->object($handler)
                    ->isIdenticalTo($result)
                ->object($this->invoke($stream)->getListener())
                    ->isInstanceOf(Event\Listener::class)
                ->boolean(Event::eventExists('hoa://Event/Stream/' . $name))
                    ->isTrue()
                ->boolean(Event::eventExists('hoa://Event/Stream/' . $name . ':close-before'))
                    ->isTrue()
                ->integer($stream->getStreamBufferSize())
                    ->isEqualTo(SUT::DEFAULT_BUFFER_SIZE);
    }

    public function case_close_event_close_before()
    {
        $self = $this;

        $this
            ->given(
                $name   = 'hoa://Test/Vfs/Foo?type=file',
                $stream = new SUT($name),
                Event::getEvent('hoa://Event/Stream/' . $name . ':close-before')->attach(
                    function (Event\Bucket $bucket) use ($self, &$called) {
                        $called = true;

                        $self
                            ->variable($bucket->getData())
                                ->isNull()
                            ->boolean($bucket->getSource()->isOpened())
                                ->isTrue();
                    }
                )
            )
            ->when($result = $stream->close())
            ->then
                ->boolean($called)
                    ->isTrue();
    }

    public function case_get_stream_name()
    {
        $this
            ->given(
                $name   = __FILE__,
                $stream = new SUT($name)
            )
            ->when($result = $stream->getStreamName())
            ->then
                ->string($result)
                    ->isEqualTo($name);
    }

    public function case_get_stream()
    {
        $this
            ->given(
                $name   = __FILE__,
                $stream = new SUT($name)
            )
            ->when($result = $stream->getStream())
            ->then
                ->resource($result)
                    ->isStream($name);
    }

    public function case_get_stream_context()
    {
        $this
            ->given(
                $name        = __FILE__,
                $contextName = 'foo',
                $context     = LUT\Context::getInstance($contextName),
                $stream      = new SUT($name, $contextName)
            )
            ->when($result = $stream->getStreamContext())
            ->then
                ->object($result)
                    ->isIdenticalTo($context);
    }

    public function case_get_stream_context_with_no_context_given()
    {
        $this
            ->given(
                $name   = __FILE__,
                $stream = new SUT($name)
            )
            ->when($result = $stream->getStreamContext())
            ->then
                ->variable($result)
                    ->isNull();
    }

    public function case_get_stream_handler()
    {
        $this
            ->given(
                $name   = __FILE__,
                $stream = new SUT($name)
            )
            ->when($result = SUT::getStreamHandler($name))
            ->then
                ->object($result)
                    ->isIdenticalTo($result);
    }

    public function case_get_stream_handler_of_unknown_stream()
    {
        $this
            ->when($result = SUT::getStreamHandler('foo'))
            ->then
                ->variable($result)
                    ->isNull();
    }

    public function case__set_stream()
    {
        $this
            ->given(
                $stream    = new SUT(__FILE__),
                $oldStream = $stream->getStream(),
                $newStream = fopen('php://memory', 'rb')
            )
            ->when($result = $stream->_setStream($newStream))
            ->then
                ->resource($result)
                    ->isIdenticalTo($oldStream)
                    ->isStream()
                ->resource($stream->getStream())
                    ->isStream()
                    ->isIdenticalTo($newStream);
    }

    public function case__set_stream_invalid_resource()
    {
        $this
            ->given($stream = new SUT(__FILE__))
            ->exception(function () use ($stream) {
                $stream->_setStream(true);
            })
                ->isInstanceOf(LUT\Exception::class);
    }

    public function case__set_stream_unknown_resource()
    {
        $this
            ->given(
                $stream = new SUT(__FILE__),
                $oldStream = $stream->getStream(),
                $newStream = fopen('php://memory', 'rb'),
                $this->function->is_resource       = false,
                $this->function->gettype           = 'resource',
                $this->function->get_resource_type = 'Unknown'
            )
            ->when($result = $stream->_setStream($newStream))
            ->then
                ->resource($result)
                    ->isIdenticalTo($oldStream)
                    ->isStream()
                ->resource($stream->getStream())
                    ->isStream()
                    ->isIdenticalTo($newStream);
    }

    public function case_is_opened()
    {
        $this
            ->given($stream = new SUT(__FILE__))
            ->when($result = $stream->isOpened())
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_is_not_opened()
    {
        $this
            ->given($stream = new SUT(__FILE__, null, true))
            ->when($result = $stream->isOpened())
            ->then
                ->boolean($result)
                    ->isFalse()

            ->when(
                $stream->open(),
                $result = $stream->isOpened()
            )
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_set_stream_timeout()
    {
        $self = $this;

        $this
            ->given(
                $stream = new SUT(__FILE__),

                $this->function->stream_set_timeout = function ($_stream, $_seconds, $_microseconds) use ($self, $stream, &$called) {
                    $called = true;

                    $self
                        ->resource($_stream)
                            ->isIdenticalTo($stream->getStream())
                        ->integer($_seconds)
                            ->isEqualTo(7)
                        ->integer($_microseconds)
                            ->isEqualTo(42);

                    return true;
                }
            )
            ->when($result = $stream->setStreamTimeout(7, 42))
            ->then
                ->boolean($result)
                    ->isTrue()
                ->boolean($called)
                    ->isTrue();
    }

    public function case_has_been_deferred()
    {
        $this
            ->given($stream = new SUT(__FILE__, null, true))
            ->when($result = $this->invoke($stream)->hasBeenDeferred())
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_has_not_been_deferred()
    {
        $this
            ->given($stream = new SUT(__FILE__))
            ->when($result = $this->invoke($stream)->hasBeenDeferred())
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_has_timed_out()
    {
        $this
            ->given(
                $stream = new SUT(__FILE__),
                $this->function->stream_get_meta_data = [
                    'timed_out' => true
                ]
            )
            ->when($result = $stream->hasTimedOut())
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_has_not_timed_out()
    {
        $this
            ->given(
                $stream = new SUT(__FILE__),
                $this->function->stream_get_meta_data = [
                    'timed_out' => false
                ]
            )
            ->when($result = $stream->hasTimedOut())
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_set_stream_blocking()
    {
        $self = $this;

        $this
            ->given(
                $stream = new SUT(__FILE__),

                $this->function->stream_set_blocking = function ($_stream, $_mode) use ($self, $stream, &$called) {
                    $called = true;

                    $self
                        ->resource($_stream)
                            ->isIdenticalTo($stream->getStream())
                        ->integer($_mode)
                            ->isEqualTo(1);

                    return true;
                }
            )
            ->when($result = $stream->setStreamBlocking(true))
            ->then
                ->boolean($result)
                    ->isTrue()
                ->boolean($called)
                    ->isTrue();
    }

    public function case_get_default_stream_buffer_size()
    {
        $self = $this;

        $this
            ->given($stream = new SUT(__FILE__))
            ->when($result = $stream->getStreamBufferSize())
            ->then
            ->integer($result)
                    ->isEqualTo(8192);
    }

    public function case_set_stream_buffer()
    {
        $self = $this;

        $this
            ->given(
                $stream = new SUT(__FILE__),

                $this->function->stream_set_write_buffer = function ($_stream, $_buffer) use ($self, $stream, &$called) {
                    $called = true;

                    $self
                        ->resource($_stream)
                            ->isIdenticalTo($stream->getStream())
                        ->integer($_buffer)
                            ->isEqualTo(42);

                    return 0;
                }
            )
            ->when($result = $stream->setStreamBuffer(42))
            ->then
                ->boolean($result)
                    ->isTrue()
                ->boolean($called)
                    ->isTrue()
                ->integer($stream->getStreamBufferSize())
                    ->isEqualTo(42);
    }

    public function case_set_stream_buffer_fail()
    {
        $self = $this;

        $this
            ->given(
                $stream              = new SUT(__FILE__),
                $oldStreamBufferSize = $stream->getStreamBufferSize(),

                $this->function->stream_set_write_buffer = function ($_stream, $_buffer) use ($self, $stream, &$called) {
                    $called = true;

                    $self
                        ->resource($_stream)
                            ->isIdenticalTo($stream->getStream())
                        ->integer($_buffer)
                            ->isEqualTo(42);

                    return 1;
                }
            )
            ->when($result = $stream->setStreamBuffer(42))
            ->then
                ->boolean($result)
                    ->isFalse()
                ->boolean($called)
                    ->isTrue()
                ->integer($stream->getStreamBufferSize())
                    ->isEqualTo($oldStreamBufferSize);
    }

    public function case_disable_stream_buffer()
    {
        $self = $this;

        $this
            ->given(
                $stream = new SUT(__FILE__),

                $this->function->stream_set_write_buffer = function ($_stream, $_buffer) use ($self, $stream, &$called) {
                    $called = true;

                    $self
                        ->resource($_stream)
                            ->isIdenticalTo($stream->getStream())
                        ->integer($_buffer)
                            ->isEqualTo(0);

                    return 0;
                }
            )
            ->when($result = $stream->disableStreamBuffer())
            ->then
                ->boolean($result)
                    ->isTrue()
                ->boolean($called)
                    ->isTrue()
                ->integer($stream->getStreamBufferSize())
                    ->isEqualTo(0);
    }

    public function case_get_stream_wrapper_name_with_no_wrapper()
    {
        $this
            ->given($stream = new SUT(__FILE__))
            ->when($result = $stream->getStreamWrapperName())
            ->then
                ->string($result)
                    ->isEqualTo('file');
    }

    public function case_get_stream_wrapper_name()
    {
        $this
            ->given($stream = new SUT('hoa://Test/Vfs/Foo?type=file'))
            ->when($result = $stream->getStreamWrapperName())
            ->then
                ->string($result)
                    ->isEqualTo('hoa');
    }

    public function case_get_stream_meta_data()
    {
        $this
            ->given($stream = new SUT(__FILE__))
            ->when($result = $stream->getStreamMetaData())
            ->then
                ->array($result)
                    ->isEqualTo([
                        'timed_out'    => false,
                        'blocked'      => true,
                        'eof'          => false,
                        'wrapper_type' => 'plainfile',
                        'stream_type'  => 'STDIO',
                        'mode'         => 'rb',
                        'unread_bytes' => 0,
                        'seekable'     => true,
                        'uri'          => __FILE__
                    ]);
    }

    public function case_is_borrowing()
    {
        $this
            ->given(
                $streamA1 = new SUT(__FILE__),
                $streamA2 = new SUT(__FILE__)
            )
            ->when($result = $streamA2->isBorrowing())
            ->then
                ->boolean($result)
                    ->isTrue()
                ->boolean($streamA1->isBorrowing())
                    ->isFalse();
    }

    public function case_is_not_borrowing()
    {
        $this
            ->given($stream = new SUT(__FILE__))
            ->when($result = $stream->isBorrowing())
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_shutdown_destructor()
    {
        $this
            ->given(
                $stream = new \Mock\Hoa\Stream\Test\Unit\SUTWithPublicClose(__FILE__),
                $this->calling($stream)->_close = function () use (&$called) {
                    $called = true;
                }
            )
            ->when($result = SUT::_Hoa_Stream())
            ->then
                ->boolean($called)
                    ->isTrue();
    }

    public function case_destruct_an_opened_stream()
    {
        $this
            ->given(
                $stream = new \Mock\Hoa\Stream\Test\Unit\SUTWithPublicClose(__FILE__),
                $this->calling($stream)->_close = function () use (&$called) {
                    $called = true;
                }
            )
            ->when($result = $stream->__destruct())
            ->then
                ->boolean($called)
                    ->isTrue();
    }

    public function case_destruct_a_deferred_stream()
    {
        $this
            ->given(
                $stream = new \Mock\Hoa\Stream\Test\Unit\SUTWithPublicClose(__FILE__, null, true),
                $this->calling($stream)->_close = function () use (&$called) {
                    $called = true;
                }
            )
            ->when($result = $stream->__destruct())
            ->then
                ->variable($called)
                    ->isNull();
    }

    public function case_protocol_reach_id()
    {
        $this
            ->given(
                $name   = 'hoa://Test/Vfs/Foo?type=file',
                $stream = new SUT($name)
            )
            ->when($result = resolve('hoa://Library/Stream#' . $name))
            ->then
                ->object($result)
                    ->isIdenticalTo($stream);
    }

    public function case_protocol_reach_unknown_id()
    {
        $this
            ->given($name = 'hoa://Test/Vfs/Foo?type=file')
            ->when($result = resolve('hoa://Library/Stream#' . $name))
            ->then
                ->variable($result)
                    ->isNull();
    }
}

class SUT extends LUT\Stream
{
    protected function &_open($streamName, LUT\Context $context = null)
    {
        if (null === $context) {
            $out = fopen($streamName, 'rb');
        } else {
            $out = fopen($streamName, 'rb', false, $context->getContext());
        }

        return $out;
    }

    protected function _close()
    {
        return fclose($this->getStream());
    }
}

class SUTWithPublicClose extends SUT
{
    public function _close()
    {
        return parent::_close();
    }
}
