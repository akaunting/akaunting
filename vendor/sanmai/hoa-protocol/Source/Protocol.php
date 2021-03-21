<?php

declare(strict_types=1);

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

namespace Hoa\Protocol;

use Hoa\Consistency;

/**
 * Root of the `hoa://` protocol.
 */
class Protocol extends Node
{
    /**
     * No resolution value.
     *
     * @const string
     */
    const NO_RESOLUTION = '/hoa/flatland';

    /**
     * Singleton.
     */
    private static $_instance = null;

    /**
     * Cache of resolver.
     */
    private static $_cache    = [];



    /**
     * Initialize the protocol.
     */
    public function __construct()
    {
        $this->initialize();

        return;
    }

    /**
     * Singleton.
     * To use the `hoa://` protocol shared by everyone.
     */
    public static function getInstance(): Protocol
    {
        if (null === static::$_instance) {
            static::$_instance = new static();
        }

        return static::$_instance;
    }

    /**
     * Initialize the protocol.
     */
    protected function initialize()
    {
        $root  = dirname(__DIR__, 3);
        $argv0 = realpath($_SERVER['argv'][0]);

        $cwd =
            'cli' === PHP_SAPI
                ? false !== $argv0 ? dirname($argv0) : ''
                : getcwd();

        $this[] = new Node(
            'Application',
            $cwd . DS,
            [
                new Node('Public', 'Public' . DS)
            ]
        );

        $this[] = new Node(
            'Data',
            dirname($cwd) . DS,
            [
                new Node(
                    'Etc',
                    'Etc' . DS,
                    [
                        new Node('Configuration', 'Configuration' . DS),
                        new Node('Locale', 'Locale' . DS)
                    ]
                ),
                new Node('Lost+found', 'Lost+found' . DS),
                new Node('Temporary', 'Temporary' . DS),
                new Node(
                    'Variable',
                    'Variable' . DS,
                    [
                        new Node('Cache', 'Cache' . DS),
                        new Node('Database', 'Database' . DS),
                        new Node('Log', 'Log' . DS),
                        new Node('Private', 'Private' . DS),
                        new Node('Run', 'Run' . DS),
                        new Node('Test', 'Test' . DS)
                    ]
                )
            ]
        );

        $this[] = new Node\Library(
            'Library',
            $root . DS . 'Hoathis' . DS . RS .
            $root . DS . 'Hoa' . DS
        );
    }

    /**
     * Resolve (unfold) an `hoa://` path to its real resource.
     *
     * If `$exists` is set to `true`, try to find the first that exists,
     * otherwise returns the first solution.  If `$unfold` is set to `true`,
     * it returns all the paths.
     */
    public function resolve(string $path, bool $exists = true, bool $unfold = false)
    {
        if (substr($path, 0, 6) !== 'hoa://') {
            if (true === is_dir($path)) {
                $path = rtrim($path, '/\\');

                if (0 === strlen($path)) {
                    $path = '/';
                }
            }

            return $path;
        }

        if (isset(self::$_cache[$path])) {
            $handle = self::$_cache[$path];
        } else {
            $out = $this->_resolve($path, $handle);

            // Not a path but a resource.
            if (!is_array($handle)) {
                return $out;
            }

            $handle = array_values(array_unique($handle, SORT_REGULAR));

            foreach ($handle as &$entry) {
                if (true === is_dir($entry)) {
                    $entry = rtrim($entry, '/\\');

                    if (0 === strlen($entry)) {
                        $entry = '/';
                    }
                }
            }

            self::$_cache[$path] = $handle;
        }

        if (true === $unfold) {
            if (true !== $exists) {
                return $handle;
            }

            $out = [];

            foreach ($handle as $solution) {
                if (file_exists($solution)) {
                    $out[] = $solution;
                }
            }

            return $out;
        }

        if (true !== $exists) {
            return $handle[0];
        }

        foreach ($handle as $solution) {
            if (file_exists($solution)) {
                return $solution;
            }
        }

        return static::NO_RESOLUTION;
    }

    /**
     * Clear the cache.
     */
    public static function clearCache()
    {
        self::$_cache = [];
    }
}

/**
 * Flex entity.
 */
Consistency::flexEntity(Protocol::class);
