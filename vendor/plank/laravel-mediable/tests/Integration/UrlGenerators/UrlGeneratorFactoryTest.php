<?php

namespace Plank\Mediable\Tests\Integration\UrlGenerators;

use League\Flysystem\PathPrefixing\PathPrefixedAdapter;
use Plank\Mediable\Exceptions\MediaUrlException;
use Plank\Mediable\Media;
use Plank\Mediable\Tests\TestCase;
use Plank\Mediable\UrlGenerators\LocalUrlGenerator;
use Plank\Mediable\UrlGenerators\UrlGeneratorFactory;
use Plank\Mediable\UrlGenerators\UrlGeneratorInterface;
use stdClass;

class UrlGeneratorFactoryTest extends TestCase
{
    public function test_it_sets_generator_for_driver()
    {
        $factory = new UrlGeneratorFactory;
        $generator = $this->createMock(UrlGeneratorInterface::class);
        $class = get_class($generator);

        $media = factory(Media::class)->make(['disk' => 'uploads']);

        $factory->setGeneratorForFilesystemDriver($class, 'local');
        $result = $factory->create($media);
        $this->assertInstanceOf($class, $result);
    }

    public function test_it_throws_exception_for_invalid_generator()
    {
        $factory = new UrlGeneratorFactory;
        $class = get_class($this->createMock(stdClass::class));
        $this->expectException(MediaUrlException::class);
        $factory->setGeneratorForFilesystemDriver($class, 'foo');
    }

    public function test_it_throws_exception_if_cant_map_to_driver()
    {
        $factory = new UrlGeneratorFactory;
        $media = factory(Media::class)->make();
        $this->expectException(MediaUrlException::class);
        $factory->create($media);
    }

    public function test_it_follows_scoped_prefix()
    {
        if (version_compare($this->app->version(), '9.30.0', '<')) {
            $this->markTestSkipped("scoped disk prefixes are only supported in laravel 9.30.0+");
        }
        // TODO: league/flysystem-path-prefixing requires PHP 8, we still support 7.4
        //  so can't include it in composer.json yet. To be fixed in next major version
        if (!class_exists(PathPrefixedAdapter::class)) {
            $this->markTestSkipped("path prefixing not installed");
        }
        $factory = new UrlGeneratorFactory;
        $factory->setGeneratorForFilesystemDriver(LocalUrlGenerator::class, 'local');
        /** @var Media $media */
        $media = factory(Media::class)->make(['disk' => 'scoped']);
        $result = $factory->create($media);
        $this->assertInstanceOf(LocalUrlGenerator::class, $result);
        $this->assertEquals('/storage/scoped/' . $media->getDiskPath(), $result->getUrl());
    }
}
