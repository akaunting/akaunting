<?php

namespace Doctrine\Common\Annotations;

use Doctrine\Common\Cache\Cache;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

use function array_map;
use function array_merge;
use function assert;
use function filemtime;
use function max;
use function time;

/**
 * A cache aware annotation reader.
 */
final class CachedReader implements Reader
{
    /** @var Reader */
    private $delegate;

    /** @var Cache */
    private $cache;

    /** @var bool */
    private $debug;

    /** @var array<string, array<object>> */
    private $loadedAnnotations = [];

    /** @var int[] */
    private $loadedFilemtimes = [];

    /**
     * @param bool $debug
     */
    public function __construct(Reader $reader, Cache $cache, $debug = false)
    {
        $this->delegate = $reader;
        $this->cache    = $cache;
        $this->debug    = (bool) $debug;
    }

    /**
     * {@inheritDoc}
     */
    public function getClassAnnotations(ReflectionClass $class)
    {
        $cacheKey = $class->getName();

        if (isset($this->loadedAnnotations[$cacheKey])) {
            return $this->loadedAnnotations[$cacheKey];
        }

        $annots = $this->fetchFromCache($cacheKey, $class);
        if ($annots === false) {
            $annots = $this->delegate->getClassAnnotations($class);
            $this->saveToCache($cacheKey, $annots);
        }

        return $this->loadedAnnotations[$cacheKey] = $annots;
    }

    /**
     * {@inheritDoc}
     */
    public function getClassAnnotation(ReflectionClass $class, $annotationName)
    {
        foreach ($this->getClassAnnotations($class) as $annot) {
            if ($annot instanceof $annotationName) {
                return $annot;
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyAnnotations(ReflectionProperty $property)
    {
        $class    = $property->getDeclaringClass();
        $cacheKey = $class->getName() . '$' . $property->getName();

        if (isset($this->loadedAnnotations[$cacheKey])) {
            return $this->loadedAnnotations[$cacheKey];
        }

        $annots = $this->fetchFromCache($cacheKey, $class);
        if ($annots === false) {
            $annots = $this->delegate->getPropertyAnnotations($property);
            $this->saveToCache($cacheKey, $annots);
        }

        return $this->loadedAnnotations[$cacheKey] = $annots;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyAnnotation(ReflectionProperty $property, $annotationName)
    {
        foreach ($this->getPropertyAnnotations($property) as $annot) {
            if ($annot instanceof $annotationName) {
                return $annot;
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodAnnotations(ReflectionMethod $method)
    {
        $class    = $method->getDeclaringClass();
        $cacheKey = $class->getName() . '#' . $method->getName();

        if (isset($this->loadedAnnotations[$cacheKey])) {
            return $this->loadedAnnotations[$cacheKey];
        }

        $annots = $this->fetchFromCache($cacheKey, $class);
        if ($annots === false) {
            $annots = $this->delegate->getMethodAnnotations($method);
            $this->saveToCache($cacheKey, $annots);
        }

        return $this->loadedAnnotations[$cacheKey] = $annots;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodAnnotation(ReflectionMethod $method, $annotationName)
    {
        foreach ($this->getMethodAnnotations($method) as $annot) {
            if ($annot instanceof $annotationName) {
                return $annot;
            }
        }

        return null;
    }

    /**
     * Clears loaded annotations.
     *
     * @return void
     */
    public function clearLoadedAnnotations()
    {
        $this->loadedAnnotations = [];
        $this->loadedFilemtimes  = [];
    }

    /**
     * Fetches a value from the cache.
     *
     * @param string $cacheKey The cache key.
     *
     * @return mixed The cached value or false when the value is not in cache.
     */
    private function fetchFromCache($cacheKey, ReflectionClass $class)
    {
        $data = $this->cache->fetch($cacheKey);
        if ($data !== false) {
            if (! $this->debug || $this->isCacheFresh($cacheKey, $class)) {
                return $data;
            }
        }

        return false;
    }

    /**
     * Saves a value to the cache.
     *
     * @param string $cacheKey The cache key.
     * @param mixed  $value    The value.
     *
     * @return void
     */
    private function saveToCache($cacheKey, $value)
    {
        $this->cache->save($cacheKey, $value);
        if (! $this->debug) {
            return;
        }

        $this->cache->save('[C]' . $cacheKey, time());
    }

    /**
     * Checks if the cache is fresh.
     *
     * @param string $cacheKey
     *
     * @return bool
     */
    private function isCacheFresh($cacheKey, ReflectionClass $class)
    {
        $lastModification = $this->getLastModification($class);
        if ($lastModification === 0) {
            return true;
        }

        return $this->cache->fetch('[C]' . $cacheKey) >= $lastModification;
    }

    /**
     * Returns the time the class was last modified, testing traits and parents
     */
    private function getLastModification(ReflectionClass $class): int
    {
        $filename = $class->getFileName();

        if (isset($this->loadedFilemtimes[$filename])) {
            return $this->loadedFilemtimes[$filename];
        }

        $parent = $class->getParentClass();

        $lastModification =  max(array_merge(
            [$filename ? filemtime($filename) : 0],
            array_map(function (ReflectionClass $reflectionTrait): int {
                return $this->getTraitLastModificationTime($reflectionTrait);
            }, $class->getTraits()),
            array_map(function (ReflectionClass $class): int {
                return $this->getLastModification($class);
            }, $class->getInterfaces()),
            $parent ? [$this->getLastModification($parent)] : []
        ));

        assert($lastModification !== false);

        return $this->loadedFilemtimes[$filename] = $lastModification;
    }

    private function getTraitLastModificationTime(ReflectionClass $reflectionTrait): int
    {
        $fileName = $reflectionTrait->getFileName();

        if (isset($this->loadedFilemtimes[$fileName])) {
            return $this->loadedFilemtimes[$fileName];
        }

        $lastModificationTime = max(array_merge(
            [$fileName ? filemtime($fileName) : 0],
            array_map(function (ReflectionClass $reflectionTrait): int {
                return $this->getTraitLastModificationTime($reflectionTrait);
            }, $reflectionTrait->getTraits())
        ));

        assert($lastModificationTime !== false);

        return $this->loadedFilemtimes[$fileName] = $lastModificationTime;
    }
}
