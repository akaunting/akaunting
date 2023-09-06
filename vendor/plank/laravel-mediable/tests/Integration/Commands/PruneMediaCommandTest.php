<?php

namespace Plank\Mediable\Tests\Integration\Commands;

use Illuminate\Contracts\Console\Kernel as Artisan;
use Plank\Mediable\Media;
use Plank\Mediable\Tests\TestCase;

class PruneMediaCommandTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->useDatabase();
        $this->useFilesystem('tmp');
        $this->withoutMockingConsoleOutput();
    }

    public function test_it_deletes_media_without_files()
    {
        $artisan = $this->getArtisan();
        $media1 = factory(Media::class)->create(['id' => 1, 'disk' => 'tmp']);
        $media2 = factory(Media::class)->create(['id' => 2, 'disk' => 'tmp']);
        $this->seedFileForMedia($media2);

        $artisan->call('media:prune', ['disk' => 'tmp']);

        $this->assertEquals([2], Media::pluck('id')->toArray());
        $this->assertEquals("Pruned 1 record(s).\n", $artisan->output());
    }

    public function test_it_prunes_directory()
    {
        $artisan = $this->getArtisan();
        $media1 = factory(Media::class)->create(
            ['id' => 1, 'disk' => 'tmp', 'directory' => '']
        );
        $media2 = factory(Media::class)->create(
            ['id' => 2, 'disk' => 'tmp', 'directory' => 'foo']
        );

        $artisan->call('media:prune', ['disk' => 'tmp', '--directory' => 'foo']);

        $this->assertEquals([1], Media::pluck('id')->toArray());
        $this->assertEquals("Pruned 1 record(s).\n", $artisan->output());
    }

    public function test_it_prunes_non_recursively()
    {
        $artisan = $this->getArtisan();
        $media1 = factory(Media::class)->create(
            ['id' => 1, 'disk' => 'tmp', 'directory' => '']
        );
        $media2 = factory(Media::class)->create(
            ['id' => 2, 'disk' => 'tmp', 'directory' => 'foo']
        );

        $artisan->call('media:prune', ['disk' => 'tmp', '--non-recursive' => true]);

        $this->assertEquals([2], Media::pluck('id')->toArray());
        $this->assertEquals("Pruned 1 record(s).\n", $artisan->output());
    }

    public function getArtisan()
    {
        return app(Artisan::class);
    }
}
