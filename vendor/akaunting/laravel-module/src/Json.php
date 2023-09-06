<?php

namespace Akaunting\Module;

use Illuminate\Filesystem\Filesystem;
use Akaunting\Module\Exceptions\InvalidJsonException;

class Json
{
    /**
     * The file path.
     *
     * @var string
     */
    protected $path;

    /**
     * The laravel filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * The attributes collection.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $attributes;

    /**
     * The constructor.
     *
     * @param mixed                             $path
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     */
    public function __construct($path, Filesystem $filesystem = null)
    {
        $this->path = (string) $path;
        $this->filesystem = $filesystem ?: new Filesystem();
        $this->attributes = Collection::make($this->getAttributes());
    }

    /**
     * Get filesystem.
     *
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Set filesystem.
     *
     * @param Filesystem $filesystem
     *
     * @return $this
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path.
     *
     * @param mixed $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = (string) $path;

        return $this;
    }

    /**
     * Make new instance.
     *
     * @param string                            $path
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return static
     */
    public static function make($path, Filesystem $filesystem = null)
    {
        return new static($path, $filesystem);
    }

    /**
     * Get file content.
     *
     * @return string
     */
    public function getContents()
    {
        return $this->filesystem->get($this->getPath());
    }

    /**
     * Get file contents as array.
     * @return array
     * @throws \Exception
     */
    public function getAttributes()
    {
        $attributes = json_decode($this->getContents(), 1);

        // any JSON parsing errors should throw an exception
        if (json_last_error() > 0) {
            throw new InvalidJsonException('Error processing file: ' . $this->getPath() . '. Error: ' . json_last_error_msg());
        }

        if (config('module.cache.enabled') === false) {
            return $attributes;
        }

        return app('cache')->remember($this->getPath(), config('module.cache.lifetime'), function () use ($attributes) {
            return $attributes;
        });
    }

    /**
     * Convert the given array data to pretty json.
     *
     * @param array $data
     *
     * @return string
     */
    public function toJsonPretty(array $data = null)
    {
        return json_encode($data ?: $this->attributes, JSON_PRETTY_PRINT);
    }

    /**
     * Update json contents from array data.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $this->attributes = new Collection(array_merge($this->attributes->toArray(), $data));

        return $this->save();
    }

    /**
     * Set a specific key & value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->attributes->offsetSet($key, $value);

        return $this;
    }

    /**
     * Save the current attributes array to the file storage.
     *
     * @return bool
     */
    public function save()
    {
        return $this->filesystem->put($this->getPath(), $this->toJsonPretty());
    }

    /**
     * Handle magic method __get.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Get the specified attribute from json file.
     *
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->attributes->get($key, $default);
    }

    /**
     * Handle call to __call method.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments = [])
    {
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $arguments);
        }

        return call_user_func_array([$this->attributes, $method], $arguments);
    }

    /**
     * Handle call to __toString method.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getContents();
    }
}
