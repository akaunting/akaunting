<?php

namespace Intervention\Image;

use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Cache\FileStore;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Cache\Repository;
use Illuminate\Filesystem\Filesystem;

class ImageCache
{
    /**
     * Cache lifetime in minutes
     *
     * @var integer
     */
    public $lifetime = 5;

    /**
     * History of name and arguments of calls performed on image
     *
     * @var array
     */
    public $calls = [];

    /**
     * Additional properties included in checksum
     *
     * @var array
     */
    public $properties = [];

    /**
     * Processed Image
     *
     * @var Intervention\Image\Image
     */
    public $image;

    /**
     * Intervention Image Manager
     *
     * @var Intervention\Image\ImageManager
     */
    public $manager;

    /**
     * Illuminate Cache Manager
     *
     * @var Illuminate\Cache\CacheManager
     */
    public $cache;

    /**
     * Create a new instance
     */
    public function __construct(ImageManager $manager = null, Cache $cache = null)
    {
        $this->manager = $manager ? $manager : new ImageManager();

        if (is_null($cache)) {
            // get laravel app
            $app = function_exists('app') ? app() : null;

            // if laravel app cache exists
            if (is_a($app, 'Illuminate\Foundation\Application')) {
                $cache = $app->make('cache');
            }

            if (is_a($cache, 'Illuminate\Cache\CacheManager')) {
                // add laravel cache and set custom cache_driver if persist
                $cache_driver = config('imagecache.cache_driver');
                $this->cache = $cache_driver ? $cache->driver($cache_driver) : $cache;
            } else {
                // define path in filesystem
                if (isset($manager->config['cache']['path'])) {
                    $path = $manager->config['cache']['path'];
                } else {
                    $path = __DIR__ . '/../../../storage/cache';
                }

                // create new default cache
                $filesystem = new Filesystem();
                $storage = new FileStore($filesystem, $path);
                $this->cache = new Repository($storage);
            }
        } else {
            $this->cache = $cache;
        }
    }

    /**
     * Magic method to capture action calls
     *
     * @param  String $name
     * @param  Array $arguments
     * @return Intervention\Image\ImageCache
     */
    public function __call($name, $arguments)
    {
        $this->registerCall($name, $arguments);

        return $this;
    }

    /**
     * Special make method to add modifed data to checksum
     *
     * @param  mixed $data
     * @return Intervention\Image\ImageCache
     */
    public function make($data)
    {
        // include "modified" property for any files
        if ($this->isFile($data)) {
            $this->setProperty('modified', filemtime((string) $data));
        }

        // register make call
        $this->__call('make', [$data]);

        return $this;
    }

    /**
     * Checks if given data is file, handles mixed input
     *
     * @param  mixed $value
     * @return boolean
     */
    protected function isFile($value)
    {
        $value = strval(str_replace("\0", "", $value));

        return strlen($value) <= PHP_MAXPATHLEN && is_file($value);
    }

    /**
     * Set custom property to be included in checksum
     *
     * @param mixed $key
     * @param mixed $value
     * @return Intervention\Image\ImageCache
     */
    public function setProperty($key, $value)
    {
        $this->properties[$key] = $value;

        return $this;
    }

    /**
     * Returns checksum of current image state
     *
     * @return string
     */
    public function checksum()
    {
        $properties = serialize($this->properties);
        $calls = serialize($this->getSanitizedCalls());

        return md5($properties . $calls);
    }

    /**
     * Register static call for later use
     *
     * @param  string $name
     * @param  array  $arguments
     * @return void
     */
    protected function registerCall($name, $arguments)
    {
        $this->calls[] = [
            'name' => $name,
            'arguments' => $arguments,
        ];
    }

    /**
     * Clears history of calls
     *
     * @return void
     */
    protected function clearCalls()
    {
        $this->calls = [];
    }

    /**
     * Clears all currently set properties
     *
     * @return void
     */
    protected function clearProperties()
    {
        $this->properties = [];
    }

    /**
     * Return unprocessed calls
     *
     * @return array
     */
    protected function getCalls()
    {
        return count($this->calls) ? $this->calls : [];
    }

    /**
     * Replace Closures in arguments with SerializableClosure
     *
     * @return array
     */
    protected function getSanitizedCalls()
    {
        $calls = $this->getCalls();

        foreach ($calls as $i => $call) {
            foreach ($call['arguments'] as $j => $argument) {
                if (is_a($argument, Closure::class)) {
                    $calls[$i]['arguments'][$j] = $this->getClosureHash($argument);
                }
            }
        }

        return $calls;
    }

    /**
     * Build hash from closure
     *
     * @param  Closure $closure
     * @return string
     */
    protected function getClosureHash(Closure $closure)
    {
        return (new HashableClosure($closure))->getHash();
    }

    /**
     * Process call on current image
     *
     * @param  array $call
     * @return void
     */
    protected function processCall($call)
    {
        $this->image = call_user_func_array(
            [
                $this->image,
                $call['name']
            ],
            $call['arguments']
        );
    }

    /**
     * Process all saved image calls on Image object
     *
     * @return Intervention\Image\Image
     */
    public function process()
    {
        // first call on manager
        $this->image = $this->manager;

        // process calls on image
        foreach ($this->getCalls() as $call) {
            $this->processCall($call);
        }

        // append checksum to image
        $this->image->cachekey = $this->checksum();

        // clean-up
        $this->clearCalls();
        $this->clearProperties();

        return $this->image;
    }

    /**
     * Get image either from cache or directly processed
     * and save image in cache if it's not saved yet
     *
     * @param  int  $lifetime
     * @param  bool $returnObj
     * @return mixed
     */
    public function get($lifetime = null, $returnObj = false)
    {
        $lifetime = is_null($lifetime) ? $this->lifetime : intval($lifetime);

        $key = $this->checksum();

        // try to get image from cache
        $cachedImageData = $this->cache->get($key);

        // if imagedata exists in cache
        if ($cachedImageData) {
            // transform into image-object
            if ($returnObj) {
                $image = $this->manager->make($cachedImageData);
                return (new CachedImage())->setFromOriginal($image, $key);
            }

            // return raw data
            return $cachedImageData;
        } else {
            // process image data
            $image = $this->process();

            // encode image data only if image is not encoded yet
            $encoded = $image->encoded ? $image->encoded : (string) $image->encode();

            // save to cache...
            $this->cache->put($key, $encoded, Carbon::now()->addMinutes($lifetime));

            // return processed image
            return $returnObj ? $image : $encoded;
        }
    }
}
