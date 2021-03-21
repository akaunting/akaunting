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
 * Class \Hoa\Exception\Idle.
 *
 * `\Hoa\Exception\Idle` is the mother exception class of libraries. The only
 * difference between `\Hoa\Exception\Idle` and its directly child
 * `\Hoa\Exception` is that the latter fires events after beeing constructed.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Idle extends \Exception
{
    /**
     * Delay processing on arguments.
     *
     * @var array
     */
    protected $_tmpArguments = null;

    /**
     * Arguments to format message.
     *
     * @var array
     */
    protected $_arguments    = null;

    /**
     * Backtrace.
     *
     * @var array
     */
    protected $_trace        = null;

    /**
     * Previous.
     *
     * @var \Exception
     */
    protected $_previous     = null;

    /**
     * Original message.
     *
     * @var string
     */
    protected $_rawMessage   = null;



    /**
     * Create an exception.
     * An exception is built with a formatted message, a code (an ID) and an
     * array that contains the list of formatted strings for the message. If
     * chaining, we can add a previous exception.
     *
     * @param   string      $message      Formatted message.
     * @param   int         $code         Code (the ID).
     * @param   array       $arguments    Arguments to format message.
     * @param   \Exception  $previous     Previous exception in chaining.
     */
    public function __construct(
        $message,
        $code = 0,
        $arguments = [],
        \Exception $previous = null
    ) {
        $this->_tmpArguments = $arguments;
        parent::__construct($message, $code, $previous);
        $this->_rawMessage   = $message;
        $this->message       = @vsprintf($message, $this->getArguments());

        return;
    }

    /**
     * Get the backtrace.
     * Do not use \Exception::getTrace() any more.
     *
     * @return  array
     */
    public function getBacktrace()
    {
        if (null === $this->_trace) {
            $this->_trace = $this->getTrace();
        }

        return $this->_trace;
    }

    /**
     * Get previous.
     * Do not use \Exception::getPrevious() any more.
     *
     * @return  \Exception
     */
    public function getPreviousThrow()
    {
        if (null === $this->_previous) {
            $this->_previous = $this->getPrevious();
        }

        return $this->_previous;
    }

    /**
     * Get arguments for the message.
     *
     * @return  array
     */
    public function getArguments()
    {
        if (null === $this->_arguments) {
            $arguments = $this->_tmpArguments;

            if (!is_array($arguments)) {
                $arguments = [$arguments];
            }

            foreach ($arguments as &$value) {
                if (null === $value) {
                    $value = '(null)';
                }
            }

            $this->_arguments = $arguments;
            unset($this->_tmpArguments);
        }

        return $this->_arguments;
    }

    /**
     * Get the raw message.
     *
     * @return  string
     */
    public function getRawMessage()
    {
        return $this->_rawMessage;
    }

    /**
     * Get the message already formatted.
     *
     * @return  string
     */
    public function getFormattedMessage()
    {
        return $this->getMessage();
    }

    /**
     * Get the source of the exception (class, method, function, main etc.).
     *
     * @return  string
     */
    public function getFrom()
    {
        $trace = $this->getBacktrace();
        $from  = '{main}';

        if (!empty($trace)) {
            $t    = $trace[0];
            $from = '';

            if (isset($t['class'])) {
                $from .= $t['class'] . '::';
            }

            if (isset($t['function'])) {
                $from .= $t['function'] . '()';
            }
        }

        return $from;
    }

    /**
     * Raise an exception as a string.
     *
     * @param   bool    $previous    Whether raise previous exception if exists.
     * @return  string
     */
    public function raise($previous = false)
    {
        $message = $this->getFormattedMessage();
        $trace   = $this->getBacktrace();
        $file    = '/dev/null';
        $line    = -1;
        $pre     = $this->getFrom();

        if (!empty($trace)) {
            $file = isset($trace['file']) ? $trace['file'] : null;
            $line = isset($trace['line']) ? $trace['line'] : null;
        }

        $pre .= ': ';

        try {
            $out =
                $pre . '(' . $this->getCode() . ') ' . $message . "\n" .
                'in ' . $this->getFile() . ' at line ' .
                $this->getLine() . '.';
        } catch (\Exception $e) {
            $out =
                $pre . '(' . $this->getCode() . ') ' . $message . "\n" .
                'in ' . $file . ' around line ' . $line . '.';
        }

        if (true === $previous &&
            null !== $previous = $this->getPreviousThrow()) {
            $out .=
                "\n\n" . '    ⬇' . "\n\n" .
                'Nested exception (' . get_class($previous) . '):' . "\n" .
                ($previous instanceof self
                    ? $previous->raise(true)
                    : $previous->getMessage());
        }

        return $out;
    }

    /**
     * Catch uncaught exception (only \Hoa\Exception\Idle and children).
     *
     * @param   \Throwable  $exception    The exception.
     * @return  void
     * @throws  \Throwable
     */
    public static function uncaught($exception)
    {
        if (!($exception instanceof self)) {
            throw $exception;
        }

        while (0 < ob_get_level()) {
            ob_end_flush();
        }

        echo
            'Uncaught exception (' . get_class($exception) . '):' . "\n" .
            $exception->raise(true);

        return;
    }

    /**
     * String representation of object.
     *
     * @return  string
     */
    public function __toString()
    {
        return $this->raise();
    }

    /**
     * Enable uncaught exception handler.
     * This is restricted to Hoa's exceptions only.
     *
     * @param   bool  $enable    Enable.
     * @return  mixed
     */
    public static function enableUncaughtHandler($enable = true)
    {
        if (false === $enable) {
            return restore_exception_handler();
        }

        return set_exception_handler(function ($exception) {
            return self::uncaught($exception);
        });
    }
}
