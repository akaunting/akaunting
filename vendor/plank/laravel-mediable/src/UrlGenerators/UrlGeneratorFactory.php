<?php
declare(strict_types=1);

namespace Plank\Mediable\UrlGenerators;

use Plank\Mediable\Exceptions\MediaUrlException;
use Plank\Mediable\Media;

class UrlGeneratorFactory
{
    /**
     * map of UrlGenerator classes to use for different filesystem drivers.
     * @var string[]
     */
    protected $driver_generators = [];

    /**
     * Get a UrlGenerator instance for a media.
     * @param  Media $media
     * @return UrlGeneratorInterface
     * @throws MediaUrlException If no generator class has been assigned for the media's disk's driver
     */
    public function create(Media $media): UrlGeneratorInterface
    {
        $driver = $this->getDriverForDisk($media->disk);
        if (array_key_exists($driver, $this->driver_generators)) {
            $class = $this->driver_generators[$driver];

            $generator = app($class);
            $generator->setMedia($media);

            return $generator;
        }

        throw MediaUrlException::generatorNotFound($media->disk, $driver);
    }

    /**
     * Set a generator subclass to use for media on a disk with a particular driver.
     * @param string $class
     * @param string $driver
     * @return void
     *
     * @throws MediaUrlException
     */
    public function setGeneratorForFilesystemDriver(string $class, string $driver): void
    {
        $this->validateGeneratorClass($class);
        $this->driver_generators[$driver] = $class;
    }

    /**
     * Verify that a class name is a valid generator.
     * @param  string $class
     * @return void
     *
     * @throws MediaUrlException If class does not exist or does not implement `UrlGenerator`
     */
    protected function validateGeneratorClass(string $class): void
    {
        if (!class_exists($class) || !is_subclass_of($class, UrlGeneratorInterface::class)) {
            throw MediaUrlException::invalidGenerator($class);
        }
    }

    /**
     * Get the driver used by a specified disk.
     * @param  string $disk
     * @return string
     */
    protected function getDriverForDisk(string $disk): string
    {
        $driver = (string) config("filesystems.disks.{$disk}.driver");
        if ($driver === 'scoped') {
            return $this->getDriverForDisk(config("filesystems.disks.{$disk}.disk"));
        }
        return $driver;
    }
}
