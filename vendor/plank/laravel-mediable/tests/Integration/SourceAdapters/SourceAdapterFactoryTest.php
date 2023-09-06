<?php

namespace Plank\Mediable\Tests\Integration\SourceAdapters;

use Plank\Mediable\Exceptions\MediaUpload\ConfigurationException;
use Plank\Mediable\SourceAdapters\SourceAdapterFactory;
use Plank\Mediable\SourceAdapters\SourceAdapterInterface;
use Plank\Mediable\Tests\TestCase;
use stdClass;

class SourceAdapterFactoryTest extends TestCase
{
    public function test_it_allows_setting_adapter_for_class()
    {
        $factory = new SourceAdapterFactory;
        $source = $this->createMock(stdClass::class);
        $sourceClass = get_class($source);
        $adapterClass = get_class($this->createMock(SourceAdapterInterface::class));

        $factory->setAdapterForClass($adapterClass, $sourceClass);
        $this->assertInstanceOf($adapterClass, $factory->create($source));
    }

    public function test_it_allows_setting_adapter_for_pattern()
    {
        $factory = new SourceAdapterFactory;
        $adapterClass = get_class($this->createMock(SourceAdapterInterface::class));

        $factory->setAdapterForPattern($adapterClass, '[abc][123]');
        $this->assertInstanceOf($adapterClass, $factory->create('b1'));
    }

    public function test_it_throws_exception_if_invalid_adapter_for_class()
    {
        $factory = new SourceAdapterFactory;
        $this->expectException(ConfigurationException::class);
        $factory->setAdapterForClass(stdClass::class, stdClass::class);
    }

    public function test_it_throws_exception_if_invalid_adapter_for_pattern()
    {
        $factory = new SourceAdapterFactory;
        $this->expectException(ConfigurationException::class);
        $factory->setAdapterForPattern(stdClass::class, 'foo');
    }

    public function test_it_throws_exception_if_no_match_for_class()
    {
        $factory = new SourceAdapterFactory;
        $this->expectException(ConfigurationException::class);
        $factory->create(new stdClass);
    }

    public function test_it_throws_exception_if_no_match_for_pattern()
    {
        $factory = new SourceAdapterFactory;
        $this->expectException(ConfigurationException::class);
        $factory->create('foo');
    }

    public function test_it_returns_adapters_unmodified()
    {
        $factory = new SourceAdapterFactory;
        $adapter = $this->createMock(SourceAdapterInterface::class);

        $this->assertEquals($adapter, $factory->create($adapter));
    }

    public function test_it_is_accessible_via_the_container()
    {
        $this->assertInstanceOf(
            SourceAdapterFactory::class,
            app('mediable.source.factory')
        );
    }
}
