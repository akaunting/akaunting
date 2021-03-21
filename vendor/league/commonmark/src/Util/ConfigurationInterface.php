<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Util;

interface ConfigurationInterface
{
    /**
     * Merge an existing array into the current configuration
     *
     * @param array<string, mixed> $config
     *
     * @return void
     */
    public function merge(array $config = []);

    /**
     * Replace the entire array with something else
     *
     * @param array<string, mixed> $config
     *
     * @return void
     */
    public function replace(array $config = []);

    /**
     * Return the configuration value at the given key, or $default if no such config exists
     *
     * The key can be a string or a slash-delimited path to a nested value
     *
     * @param string|null $key
     * @param mixed|null  $default
     *
     * @return mixed|null
     */
    public function get(?string $key = null, $default = null);

    /**
     * Set the configuration value at the given key
     *
     * The key can be a string or a slash-delimited path to a nested value
     *
     * @param string     $key
     * @param mixed|null $value
     *
     * @return void
     */
    public function set(string $key, $value = null);
}
