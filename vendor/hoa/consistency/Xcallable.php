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

namespace Hoa\Consistency;

use Hoa\Event;
use Hoa\Stream;

/**
 * Class Hoa\Consistency\Xcallable.
 *
 * Build a callable object, i.e. function, class::method, object->method or
 * closure, they all have the same behaviour. This callable is an extension of
 * native PHP callable (aka callback) to integrate Hoa's structures.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Xcallable
{
    /**
     * Callback, with the PHP format.
     *
     * @var mixed
     */
    protected $_callback = null;

    /**
     * Callable hash.
     *
     * @var string
     */
    protected $_hash     = null;



    /**
     * Build a callback.
     * Accepted forms:
     *     * `'function'`,
     *     * `'class::method'`,
     *     * `'class', 'method'`,
     *     * `$object, 'method'`,
     *     * `$object, ''`,
     *     * `function (…) { … }`,
     *     * `['class', 'method']`,
     *     * `[$object, 'method']`.
     *
     * @param   mixed   $call    First callable part.
     * @param   mixed   $able    Second callable part (if needed).
     */
    public function __construct($call, $able = '')
    {
        if ($call instanceof \Closure) {
            $this->_callback = $call;

            return;
        }

        if (!is_string($able)) {
            throw new Exception(
                'Bad callback form; the able part must be a string.',
                0
            );
        }

        if ('' === $able) {
            if (is_string($call)) {
                if (false === strpos($call, '::')) {
                    if (!function_exists($call)) {
                        throw new Exception(
                            'Bad callback form; function %s does not exist.',
                            1,
                            $call
                        );
                    }

                    $this->_callback = $call;

                    return;
                }

                list($call, $able) = explode('::', $call);
            } elseif (is_object($call)) {
                if ($call instanceof Stream\IStream\Out) {
                    $able = null;
                } elseif (method_exists($call, '__invoke')) {
                    $able = '__invoke';
                } else {
                    throw new Exception(
                        'Bad callback form; an object but without a known ' .
                        'method.',
                        2
                    );
                }
            } elseif (is_array($call) && isset($call[0])) {
                if (!isset($call[1])) {
                    return $this->__construct($call[0]);
                }

                return $this->__construct($call[0], $call[1]);
            } else {
                throw new Exception(
                    'Bad callback form.',
                    3
                );
            }
        }

        $this->_callback = [$call, $able];

        return;
    }

    /**
     * Call the callable.
     *
     * @param   ...
     * @return  mixed
     */
    public function __invoke()
    {
        $arguments = func_get_args();
        $valid     = $this->getValidCallback($arguments);

        return call_user_func_array($valid, $arguments);
    }

    /**
     * Distribute arguments according to an array.
     *
     * @param   array  $arguments    Arguments.
     * @return  mixed
     */
    public function distributeArguments(array $arguments)
    {
        return call_user_func_array([$this, '__invoke'], $arguments);
    }

    /**
     * Get a valid callback in the PHP meaning.
     *
     * @param   array   &$arguments    Arguments (could determine method on an
     *                                 object if not precised).
     * @return  mixed
     */
    public function getValidCallback(array &$arguments = [])
    {
        $callback = $this->_callback;
        $head     = null;

        if (isset($arguments[0])) {
            $head = &$arguments[0];
        }

        // If method is undetermined, we find it (we understand event bucket and
        // stream).
        if (null !== $head &&
            is_array($callback) &&
            null === $callback[1]) {
            if ($head instanceof Event\Bucket) {
                $head = $head->getData();
            }

            switch ($type = gettype($head)) {
                case 'string':
                    if (1 === strlen($head)) {
                        $method = 'writeCharacter';
                    } else {
                        $method = 'writeString';
                    }

                    break;

                case 'boolean':
                case 'integer':
                case 'array':
                    $method = 'write' . ucfirst($type);

                    break;

                case 'double':
                    $method = 'writeFloat';

                    break;

                default:
                    $method = 'writeAll';
                    $head   = $head . "\n";
            }

            $callback[1] = $method;
        }

        return $callback;
    }

    /**
     * Get hash.
     * Will produce:
     *     * function#…;
     *     * class#…::…;
     *     * object(…)#…::…;
     *     * closure(…).
     *
     * @return  string
     */
    public function getHash()
    {
        if (null !== $this->_hash) {
            return $this->_hash;
        }

        $_ = &$this->_callback;

        if (is_string($_)) {
            return $this->_hash = 'function#' . $_;
        }

        if (is_array($_)) {
            return
                $this->_hash =
                    (is_object($_[0])
                        ? 'object(' . spl_object_hash($_[0]) . ')' .
                          '#' . get_class($_[0])
                        : 'class#' . $_[0]) .
                    '::' .
                    (null !== $_[1]
                        ? $_[1]
                        : '???');
        }

        return $this->_hash = 'closure(' . spl_object_hash($_) . ')';
    }

    /**
     * Get appropriated reflection instance.
     *
     * @param   ...
     * @return  \Reflector
     */
    public function getReflection()
    {
        $arguments = func_get_args();
        $valid     = $this->getValidCallback($arguments);

        if (is_string($valid)) {
            return new \ReflectionFunction($valid);
        }

        if ($valid instanceof \Closure) {
            return new \ReflectionFunction($valid);
        }

        if (is_array($valid)) {
            if (is_string($valid[0])) {
                if (false === method_exists($valid[0], $valid[1])) {
                    return new \ReflectionClass($valid[0]);
                }

                return new \ReflectionMethod($valid[0], $valid[1]);
            }

            $object = new \ReflectionObject($valid[0]);

            if (null === $valid[1]) {
                return $object;
            }

            return $object->getMethod($valid[1]);
        }
    }

    /**
     * Return the hash.
     *
     * @return  string
     */
    public function __toString()
    {
        return $this->getHash();
    }
}
