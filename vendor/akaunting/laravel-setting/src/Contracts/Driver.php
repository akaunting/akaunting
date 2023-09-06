<?php

namespace Akaunting\Setting\Contracts;

use Akaunting\Setting\Support\Arr;
use Illuminate\Support\Facades\Cache;

abstract class Driver
{
    /**
     * The settings data.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Whether the store has changed since it was last loaded.
     *
     * @var bool
     */
    protected $unsaved = false;

    /**
     * Whether the settings data are loaded.
     *
     * @var bool
     */
    protected $loaded = false;

    /**
     * Include and merge with fallbacks
     *
     * @var bool
     */
    protected $with_fallback = true;

    /**
     * Excludes fallback data
     */
    public function withoutFallback()
    {
        $this->with_fallback = false;

        return $this;
    }

    /**
     * Get a specific key from the settings data.
     *
     * @param string|array $key
     * @param mixed        $default Optional default value.
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (!$this->checkExtraColumns()) {
            return false;
        }

        $this->load();

        return Arr::get($this->data, $key, $default);
    }

    /**
     * Get the fallback value if default is null.
     *
     * @param string|array $key
     * @param mixed        $default
     *
     * @return mixed
     */
    public function getFallback($key, $default = null)
    {
        if (($default !== null) || is_array($key)) {
            return $default;
        }

        return Arr::get((array) config('setting.fallback'), $key);
    }

    /**
     * Check if the given value is same as fallback.
     *
     * @param string $key
     * @param string $value
     *
     * @return bool
     */
    public function isEqualToFallback($key, $value)
    {
        return (string) $this->getFallback($key) == (string) $value;
    }

    /**
     * Determine if a key exists in the settings data.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        if (!$this->checkExtraColumns()) {
            return false;
        }

        $this->load();

        return Arr::has($this->data, $key);
    }

    /**
     * Set a specific key to a value in the settings data.
     *
     * @param string|array $key   Key string or associative array of key => value
     * @param mixed        $value Optional only if the first argument is an array
     */
    public function set($key, $value = null)
    {
        if (!$this->checkExtraColumns()) {
            return;
        }

        $this->load();
        $this->unsaved = true;

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                Arr::set($this->data, $k, $v);
            }
        } else {
            Arr::set($this->data, $key, $value);
        }
    }

    /**
     * Unset a key in the settings data.
     *
     * @param string $key
     */
    public function forget($key)
    {
        if (!$this->checkExtraColumns()) {
            return;
        }

        $this->unsaved = true;

        if ($this->has($key)) {
            Arr::forget($this->data, $key);
        }
    }

    /**
     * Unset all keys in the settings data.
     *
     * @return void
     */
    public function forgetAll()
    {
        if (!$this->checkExtraColumns()) {
            return;
        }

        if (config('setting.cache.enabled')) {
            Cache::forget($this->getCacheKey());
        }

        $this->unsaved = true;
        $this->data = [];
    }

    /**
     * Get all settings data.
     *
     * @return array|bool
     */
    public function all()
    {
        if (!$this->checkExtraColumns()) {
            return [];
        }

        $this->load();

        return $this->data;
    }

    /**
     * Save any changes done to the settings data.
     *
     * @return void
     */
    public function save()
    {
        if (!$this->checkExtraColumns()) {
            return;
        }

        if (!$this->unsaved) {
            // either nothing has been changed, or data has not been loaded, so
            // do nothing by returning early
            return;
        }

        if (config('setting.cache.enabled') && config('setting.cache.auto_clear')) {
            Cache::forget($this->getCacheKey());
        }

        $this->write($this->data);
        $this->unsaved = false;
    }

    /**
     * Make sure data is loaded.
     *
     * @param $force Force a reload of data. Default false.
     */
    public function load($force = false)
    {
        if (!$this->checkExtraColumns()) {
            return;
        }

        if ($this->loaded && !$force) {
            return;
        }

        $fallback_data = $this->with_fallback ? config('setting.fallback') : [];
        $driver_data = $this->readData();

        $this->data = Arr::merge((array) $fallback_data, (array) $driver_data);
        $this->loaded = true;
    }

    /**
     * Read data from driver or cache
     *
     * @return array
     */
    public function readData()
    {
        if (config('setting.cache.enabled')) {
            return $this->readDataFromCache();
        }

        return $this->read();
    }

    /**
     * Read data from cache
     *
     * @return array
     */
    public function readDataFromCache()
    {
        return Cache::remember($this->getCacheKey(), config('setting.cache.ttl'), function () {
            return $this->read();
        });
    }

    /**
     * Check if extra columns are set up.
     *
     * @return boolean
     */
    public function checkExtraColumns()
    {
        if (!$required_extra_columns = config('setting.required_extra_columns')) {
            return true;
        }

        if (array_keys_exists($required_extra_columns, $this->getExtraColumns())) {
            return true;
        }

        return false;
    }

    /**
     * Get cache key based on extra columns.
     *
     * @return string
     */
    public function getCacheKey()
    {
        $key = config('setting.cache.key');

        foreach ($this->getExtraColumns() as $name => $value) {
            $key .= '_' . $name . '_' . $value;
        }

        return $key;
    }

    /**
     * Get extra columns added to the rows.
     *
     * @return array
     */
    abstract protected function getExtraColumns();

    /**
     * Read data from driver.
     *
     * @return array
     */
    abstract protected function read();

    /**
     * Write data to driver.
     *
     * @param  array  $data
     *
     * @return void
     */
    abstract protected function write(array $data);
}
