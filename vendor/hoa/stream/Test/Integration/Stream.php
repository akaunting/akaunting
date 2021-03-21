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

namespace Hoa\Stream\Test\Integration;

use Hoa\Event;
use Hoa\Stream as LUT;
use Hoa\Test;

/**
 * Class \Hoa\Stream\Test\Integration\Stream.
 *
 * Test suite of the stream class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Stream extends Test\Integration\Suite
{
    public function case_notifications()
    {
        $self = $this;

        $this
            ->given(
                $port = mt_rand(10000, 12000),
                exec(
                    sprintf(
                        'php -S 127.0.0.1:%d -t %s > /dev/null 2>&1 & echo $! && sleep 0.2',
                        $port,
                        dirname(__DIR__) . DS . 'Fixtures'
                    ),
                    $outputs
                ),
                $pid    = $outputs[0],
                $stream = new SUT('http://127.0.0.1:' . $port, null, true),

                $stream->on(
                    'connect',
                    function (Event\Bucket $bucket) use ($self, &$connectCalled) {
                        $connectCalled = true;
                        $data          = $bucket->getData();

                        $self
                            ->array($data)
                                ->isEqualTo([
                                    'code'        => 0,
                                    'severity'    => 0,
                                    'message'     => null,
                                    'transferred' => 0,
                                    'max'         => 0
                                ]);
                    }
                ),
                $stream->on(
                    'mimetype',
                    function (Event\Bucket $bucket) use ($self, &$mimetypeCalled) {
                        $mimetypeCalled = true;
                        $data           = $bucket->getData();

                        $self
                            ->array($data)
                                ->isEqualTo([
                                    'code'        => 0,
                                    'severity'    => 0,
                                    'message'     => 'text/html; charset=UTF-8',
                                    'transferred' => 0,
                                    'max'         => 0
                                ]);
                    }
                ),
                $stream->on(
                    'size',
                    function (Event\Bucket $bucket) use ($self, &$sizeCalled) {
                        $sizeCalled = true;
                        $data       = $bucket->getData();

                        $self
                            ->array($data)
                            ->isEqualTo([
                                'code'        => 0,
                                'severity'    => 0,
                                'message'     => 'Content-Length: 14',
                                'transferred' => 0,
                                'max'         => 14
                            ]);
                    }
                ),
                $stream->on(
                    'progress',
                    function (Event\Bucket $bucket) use ($self, &$progressCalled) {
                        $progressCalled = true;
                        $data           = $bucket->getData();

                        $self
                            ->array($data)
                            ->isEqualTo([
                                'code'        => 0,
                                'severity'    => 0,
                                'message'     => null,
                                'transferred' => 0,
                                'max'         => 14
                            ]);
                    }
                )
            )
            ->when($stream->open())
            ->then
                ->boolean($connectCalled)
                    ->isTrue()
                ->boolean($mimetypeCalled)
                    ->isTrue()
                ->boolean($sizeCalled)
                    ->isTrue()
                ->boolean($progressCalled)
                    ->isTrue()
                ->let(!empty($pid) && exec('kill ' . $pid));
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
