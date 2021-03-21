<?php

namespace Doctrine\Common\Annotations;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use RuntimeException;

use function chmod;
use function file_put_contents;
use function filemtime;
use function gettype;
use function is_dir;
use function is_file;
use function is_int;
use function is_writable;
use function mkdir;
use function rename;
use function rtrim;
use function serialize;
use function sha1;
use function sprintf;
use function strtr;
use function tempnam;
use function uniqid;
use function unlink;
use function var_export;

/**
 * File cache reader for annotations.
 *
 * @deprecated the FileCacheReader is deprecated and will be removed
 *             in version 2.0.0 of doctrine/annotations. Please use the
 *             {@see \Doctrine\Common\Annotations\CachedReader} instead.
 */
class FileCacheReader implements Reader
{
    /** @var Reader */
    private $reader;

    /** @var string */
    private $dir;

    /** @var bool */
    private $debug;

    /** @phpstan-var array<string, list<object>> */
    private $loadedAnnotations = [];

    /** @var array<string, string> */
    private $classNameHashes = [];

    /** @var int */
    private $umask;

    /**
     * @param string $cacheDir
     * @param bool   $debug
     * @param int    $umask
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Reader $reader, $cacheDir, $debug = false, $umask = 0002)
    {
        if (! is_int($umask)) {
            throw new InvalidArgumentException(sprintf(
                'The parameter umask must be an integer, was: %s',
                gettype($umask)
            ));
        }

        $this->reader = $reader;
        $this->umask  = $umask;

        if (! is_dir($cacheDir) && ! @mkdir($cacheDir, 0777 & (~$this->umask), true)) {
            throw new InvalidArgumentException(sprintf(
                'The directory "%s" does not exist and could not be created.',
                $cacheDir
            ));
        }

        $this->dir   = rtrim($cacheDir, '\\/');
        $this->debug = $debug;
    }

    /**
     * {@inheritDoc}
     */
    public function getClassAnnotations(ReflectionClass $class)
    {
        if (! isset($this->classNameHashes[$class->name])) {
            $this->classNameHashes[$class->name] = sha1($class->name);
        }

        $key = $this->classNameHashes[$class->name];

        if (isset($this->loadedAnnotations[$key])) {
            return $this->loadedAnnotations[$key];
        }

        $path = $this->dir . '/' . strtr($key, '\\', '-') . '.cache.php';
        if (! is_file($path)) {
            $annot = $this->reader->getClassAnnotations($class);
            $this->saveCacheFile($path, $annot);

            return $this->loadedAnnotations[$key] = $annot;
        }

        $filename = $class->getFilename();
        if (
            $this->debug
            && $filename !== false
            && filemtime($path) < filemtime($filename)
        ) {
            @unlink($path);

            $annot = $this->reader->getClassAnnotations($class);
            $this->saveCacheFile($path, $annot);

            return $this->loadedAnnotations[$key] = $annot;
        }

        return $this->loadedAnnotations[$key] = include $path;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyAnnotations(ReflectionProperty $property)
    {
        $class = $property->getDeclaringClass();
        if (! isset($this->classNameHashes[$class->name])) {
            $this->classNameHashes[$class->name] = sha1($class->name);
        }

        $key = $this->classNameHashes[$class->name] . '$' . $property->getName();

        if (isset($this->loadedAnnotations[$key])) {
            return $this->loadedAnnotations[$key];
        }

        $path = $this->dir . '/' . strtr($key, '\\', '-') . '.cache.php';
        if (! is_file($path)) {
            $annot = $this->reader->getPropertyAnnotations($property);
            $this->saveCacheFile($path, $annot);

            return $this->loadedAnnotations[$key] = $annot;
        }

        $filename = $class->getFilename();
        if (
            $this->debug
            && $filename !== false
            && filemtime($path) < filemtime($filename)
        ) {
            @unlink($path);

            $annot = $this->reader->getPropertyAnnotations($property);
            $this->saveCacheFile($path, $annot);

            return $this->loadedAnnotations[$key] = $annot;
        }

        return $this->loadedAnnotations[$key] = include $path;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodAnnotations(ReflectionMethod $method)
    {
        $class = $method->getDeclaringClass();
        if (! isset($this->classNameHashes[$class->name])) {
            $this->classNameHashes[$class->name] = sha1($class->name);
        }

        $key = $this->classNameHashes[$class->name] . '#' . $method->getName();

        if (isset($this->loadedAnnotations[$key])) {
            return $this->loadedAnnotations[$key];
        }

        $path = $this->dir . '/' . strtr($key, '\\', '-') . '.cache.php';
        if (! is_file($path)) {
            $annot = $this->reader->getMethodAnnotations($method);
            $this->saveCacheFile($path, $annot);

            return $this->loadedAnnotations[$key] = $annot;
        }

        $filename = $class->getFilename();
        if (
            $this->debug
            && $filename !== false
            && filemtime($path) < filemtime($filename)
        ) {
            @unlink($path);

            $annot = $this->reader->getMethodAnnotations($method);
            $this->saveCacheFile($path, $annot);

            return $this->loadedAnnotations[$key] = $annot;
        }

        return $this->loadedAnnotations[$key] = include $path;
    }

    /**
     * Saves the cache file.
     *
     * @param string $path
     * @param mixed  $data
     *
     * @return void
     */
    private function saveCacheFile($path, $data)
    {
        if (! is_writable($this->dir)) {
            throw new InvalidArgumentException(sprintf(
                <<<'EXCEPTION'
The directory "%s" is not writable. Both the webserver and the console user need access.
You can manage access rights for multiple users with "chmod +a".
If your system does not support this, check out the acl package.,
EXCEPTION
                ,
                $this->dir
            ));
        }

        $tempfile = tempnam($this->dir, uniqid('', true));

        if ($tempfile === false) {
            throw new RuntimeException(sprintf('Unable to create tempfile in directory: %s', $this->dir));
        }

        @chmod($tempfile, 0666 & (~$this->umask));

        $written = file_put_contents(
            $tempfile,
            '<?php return unserialize(' . var_export(serialize($data), true) . ');'
        );

        if ($written === false) {
            throw new RuntimeException(sprintf('Unable to write cached file to: %s', $tempfile));
        }

        @chmod($tempfile, 0666 & (~$this->umask));

        if (rename($tempfile, $path) === false) {
            @unlink($tempfile);

            throw new RuntimeException(sprintf('Unable to rename %s to %s', $tempfile, $path));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getClassAnnotation(ReflectionClass $class, $annotationName)
    {
        $annotations = $this->getClassAnnotations($class);

        foreach ($annotations as $annotation) {
            if ($annotation instanceof $annotationName) {
                return $annotation;
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodAnnotation(ReflectionMethod $method, $annotationName)
    {
        $annotations = $this->getMethodAnnotations($method);

        foreach ($annotations as $annotation) {
            if ($annotation instanceof $annotationName) {
                return $annotation;
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyAnnotation(ReflectionProperty $property, $annotationName)
    {
        $annotations = $this->getPropertyAnnotations($property);

        foreach ($annotations as $annotation) {
            if ($annotation instanceof $annotationName) {
                return $annotation;
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
    }
}
