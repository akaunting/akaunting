<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * Original code based on the CommonMark JS reference parser (https://bitly.com/commonmark-js)
 *  - (c) John MacFarlane
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Util;

final class Configuration implements ConfigurationInterface
{
    /** @var array<string, mixed> */
    private $config;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function merge(array $config = [])
    {
        $this->config = \array_replace_recursive($this->config, $config);
    }

    public function replace(array $config = [])
    {
        $this->config = $config;
    }

    public function get(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->config;
        }

        // accept a/b/c as ['a']['b']['c']
        if (\strpos($key, '/')) {
            return $this->getConfigByPath($key, $default);
        }

        if (!isset($this->config[$key])) {
            return $default;
        }

        return $this->config[$key];
    }

    public function set(string $key, $value = null)
    {
        // accept a/b/c as ['a']['b']['c']
        if (\strpos($key, '/')) {
            $this->setByPath($key, $value);
        }

        $this->config[$key] = $value;
    }

    /**
     * @param string      $keyPath
     * @param string|null $default
     *
     * @return mixed|null
     */
    private function getConfigByPath(string $keyPath, $default = null)
    {
        $keyArr = \explode('/', $keyPath);
        $data = $this->config;
        foreach ($keyArr as $k) {
            if (!\is_array($data) || !isset($data[$k])) {
                return $default;
            }

            $data = $data[$k];
        }

        return $data;
    }

    /**
     * @param string      $keyPath
     * @param string|null $value
     */
    private function setByPath(string $keyPath, $value = null): void
    {
        $keyArr = \explode('/', $keyPath);
        $pointer = &$this->config;
        while (($k = \array_shift($keyArr)) !== null) {
            if (!\is_array($pointer)) {
                $pointer = [];
            }

            if (!isset($pointer[$k])) {
                $pointer[$k] = null;
            }

            $pointer = &$pointer[$k];
        }

        $pointer = $value;
    }
}
