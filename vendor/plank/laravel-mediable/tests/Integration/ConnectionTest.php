<?php

namespace Plank\Mediable\Tests\Integration;

use Plank\Mediable\Media;
use Plank\Mediable\Tests\Mocks\SampleMediable;
use Plank\Mediable\Tests\TestCase;

class ConnectionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setupConnection();
        $this->useDatabase();
    }

    public function test_it_can_use_different_connection()
    {
        $media = factory(Media::class)->create(['id' => 1]);
        $mediable = factory(SampleMediable::class)->create();
        $mediable->attachMedia($media, 'foo');

        $this->assertEquals('my_connection', $media->getConnectionName());
        $this->assertDatabaseHas($media->getTable(), ['id' => 1], 'my_connection');
        $this->assertDatabaseHas(config('mediable.mediables_table'), [
            'media_id' => $media->getKey(),
            'mediable_type' => get_class($mediable),
            'mediable_id' => $mediable->getKey(),
        ], 'my_connection');
        $this->assertEquals(1, $mediable->firstMedia('foo')->id);
    }

    protected function setupConnection()
    {
        $this->app['config']->set('database.connections.my_connection', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => 'my__',
        ]);

        $this->app['config']->set('mediable.connection_name', 'my_connection');
    }
}
