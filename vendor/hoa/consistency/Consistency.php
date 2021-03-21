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

namespace Hoa\Consistency
{

/**
 * Class Hoa\Consistency\Consistency.
 *
 * This class is a collection of tools to ensure foreward and backward
 * compatibility.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Consistency
{
    /**
     * Check if an entity exists (class, interface, trait…).
     *
     * @param   string  $entityName    Entity name.
     * @param   bool    $autoloader    Run autoloader if necessary.
     * @return  bool
     */
    public static function entityExists($entityName, $autoloader = false)
    {
        return
            class_exists($entityName, $autoloader) ||
            interface_exists($entityName, false)   ||
            trait_exists($entityName, false);
    }

    /**
     * Get the shortest name for an entity.
     *
     * @param   string  $entityName    Entity name.
     * @return  string
     */
    public static function getEntityShortestName($entityName)
    {
        $parts = explode('\\', $entityName);
        $count = count($parts);

        if (1 >= $count) {
            return $entityName;
        }

        if ($parts[$count - 2] === $parts[$count - 1]) {
            return implode('\\', array_slice($parts, 0, -1));
        }

        return $entityName;
    }

    /**
     * Declare a flex entity (for nested library).
     *
     * @param   string  $entityName    Entity name.
     * @return  bool
     */
    public static function flexEntity($entityName)
    {
        return class_alias(
            $entityName,
            static::getEntityShortestName($entityName),
            false
        );
    }

    /**
     * Whether a word is reserved or not.
     *
     * @param   string  $word    Word.
     * @return  bool
     */
    public static function isKeyword($word)
    {
        static $_list = [
            // PHP keywords.
            '__halt_compiler',
            'abstract',
            'and',
            'array',
            'as',
            'bool',
            'break',
            'callable',
            'case',
            'catch',
            'class',
            'clone',
            'const',
            'continue',
            'declare',
            'default',
            'die',
            'do',
            'echo',
            'else',
            'elseif',
            'empty',
            'enddeclare',
            'endfor',
            'endforeach',
            'endif',
            'endswitch',
            'endwhile',
            'eval',
            'exit',
            'extends',
            'false',
            'final',
            'float',
            'for',
            'foreach',
            'function',
            'global',
            'goto',
            'if',
            'implements',
            'include',
            'include_once',
            'instanceof',
            'insteadof',
            'int',
            'interface',
            'isset',
            'list',
            'mixed',
            'namespace',
            'new',
            'null',
            'numeric',
            'object',
            'or',
            'print',
            'private',
            'protected',
            'public',
            'require',
            'require_once',
            'resource',
            'return',
            'static',
            'string',
            'switch',
            'throw',
            'trait',
            'true',
            'try',
            'unset',
            'use',
            'var',
            'void',
            'while',
            'xor',
            'yield',

            // Compile-time constants.
            '__class__',
            '__dir__',
            '__file__',
            '__function__',
            '__line__',
            '__method__',
            '__namespace__',
            '__trait__'
        ];

        return in_array(strtolower($word), $_list);
    }

    /**
     * Whether an ID is a valid PHP identifier.
     *
     * @param   string  $id    ID.
     * @return  bool
     */
    public static function isIdentifier($id)
    {
        return 0 !== preg_match(
            '#^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x80-\xff]*$#',
            $id
        );
    }

    /**
     * Register a register shutdown function.
     * It may be analogous to a super static destructor.
     *
     * @param   callable  $callable    Callable.
     * @return  bool
     */
    public static function registerShutdownFunction($callable)
    {
        return register_shutdown_function($callable);
    }

    /**
     * Get PHP executable.
     *
     * @return  string
     */
    public static function getPHPBinary()
    {
        if (defined('PHP_BINARY')) {
            return PHP_BINARY;
        }

        if (isset($_SERVER['_'])) {
            return $_SERVER['_'];
        }

        foreach (['', '.exe'] as $extension) {
            if (file_exists($_ = PHP_BINDIR . DS . 'php' . $extension)) {
                return realpath($_);
            }
        }

        return null;
    }

    /**
     * Generate an Universal Unique Identifier (UUID).
     *
     * @return  string
     */
    public static function uuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}

}

namespace
{

if (70000 > PHP_VERSION_ID && false === interface_exists('Throwable', false)) {
    /**
     * Implement a fake Throwable class, introduced in PHP7.0.
     */
    interface Throwable
    {
        public function getMessage();
        public function getCode();
        public function getFile();
        public function getLine();
        public function getTrace();
        public function getPrevious();
        public function getTraceAsString();
        public function __toString();
    }
}

/**
 * Define TLSv* constants, introduced in PHP 5.5.
 */
if (50600 > PHP_VERSION_ID) {
    $define = function ($constantName, $constantValue, $case = false) {
        if (!defined($constantName)) {
            return define($constantName, $constantValue, $case);
        }

        return false;
    };

    $define('STREAM_CRYPTO_METHOD_TLSv1_0_SERVER', 8);
    $define('STREAM_CRYPTO_METHOD_TLSv1_1_SERVER', 16);
    $define('STREAM_CRYPTO_METHOD_TLSv1_2_SERVER', 32);
    $define('STREAM_CRYPTO_METHOD_ANY_SERVER', 62);

    $define('STREAM_CRYPTO_METHOD_TLSv1_0_CLIENT', 9);
    $define('STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT', 17);
    $define('STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT', 33);
    $define('STREAM_CRYPTO_METHOD_ANY_CLIENT', 63);
}

if (!function_exists('curry')) {
    /**
     * Curry.
     * Example:
     *     $c = curry('str_replace', …, …, 'foobar');
     *     var_dump($c('foo', 'baz')); // bazbar
     *     $c = curry('str_replace', 'foo', 'baz', …);
     *     var_dump($c('foobarbaz')); // bazbarbaz
     * Nested curries also work:
     *     $c1 = curry('str_replace', …, …, 'foobar');
     *     $c2 = curry($c1, 'foo', …);
     *     var_dump($c2('baz')); // bazbar
     * Obviously, as the first argument is a callable, we can combine this with
     * \Hoa\Consistency\Xcallable ;-).
     * The “…” character is the HORIZONTAL ELLIPSIS Unicode character (Unicode:
     * 2026, UTF-8: E2 80 A6).
     *
     * @param   mixed  $callable    Callable (two parts).
     * @param   ...    ...          Arguments.
     * @return  \Closure
     */
    function curry($callable)
    {
        $arguments = func_get_args();
        array_shift($arguments);
        $ii        = array_keys($arguments, …, true);

        return function () use ($callable, $arguments, $ii) {
            return call_user_func_array(
                $callable,
                array_replace($arguments, array_combine($ii, func_get_args()))
            );
        };
    }
}

/**
 * Flex entity.
 */
Hoa\Consistency\Consistency::flexEntity('Hoa\Consistency\Consistency');

}
