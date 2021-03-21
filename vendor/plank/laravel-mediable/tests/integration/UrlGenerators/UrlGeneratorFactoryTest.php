<?php

use Plank\Mediable\Exceptions\MediaUrlException;
use Plank\Mediable\Media;
use Plank\Mediable\UrlGenerators\UrlGeneratorFactory;
use Plank\Mediable\UrlGenerators\UrlGeneratorInterface;

class UrlGeneratorFactoryTest extends TestCase
{
    public function test_it_sets_generator_for_driver()
    {
        $factory = new UrlGeneratorFactory;
        $generator = $this->getMockClass(UrlGeneratorInterface::class);

        $media = factory(Media::class)->make(['disk' => 'uploads']);

        $factory->setGeneratorForFilesystemDriver($generator, 'local');
        $result = $factory->create($media);
        $this->assertInstanceOf($generator, $result);
    }

    public function test_it_throws_exception_for_invalid_generator()
    {
        $factory = new UrlGeneratorFactory;
        $class = $this->getMockClass(stdClass::class);
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
}
