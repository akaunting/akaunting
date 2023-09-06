<?php

namespace Plank\Mediable\Tests\Integration\Commands;

use Illuminate\Contracts\Console\Kernel as Artisan;
use Illuminate\Filesystem\FilesystemManager;
use Plank\Mediable\Commands\ImportMediaCommand;
use Plank\Mediable\Media;
use Plank\Mediable\Tests\TestCase;

class ImportMediaCommandTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->useDatabase();
        $this->useFilesystem('tmp');
        $this->withoutMockingConsoleOutput();
    }

    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('mediable.allow_unrecognized_types', true);
        $app['config']->set('mediable.strict_type_checking', false);
    }

    public function test_it_creates_media_for_unmatched_files()
    {
        $artisan = $this->getArtisan();
        $media1 = factory(Media::class)->make(['disk' => 'tmp', 'filename' => 'foo']);
        $media2 = factory(Media::class)->create(['disk' => 'tmp', 'filename' => 'bar']);
        $this->seedFileForMedia($media1);
        $this->seedFileForMedia($media2);

        $artisan->call('media:import', ['disk' => 'tmp']);

        $this->assertEquals("Imported 1 file(s).\n", $artisan->output());
        $this->assertEquals(
            ['bar', 'foo'],
            Media::orderBy('filename')->pluck('filename')->toArray()
        );
    }

    public function test_it_creates_media_for_unmatched_files_in_directory()
    {
        $artisan = $this->getArtisan();
        $media1 = factory(Media::class)->make(
            ['disk' => 'tmp', 'directory' => 'a', 'filename' => 'foo']
        );
        $media2 = factory(Media::class)->make(
            ['disk' => 'tmp', 'directory' => 'a/b', 'filename' => 'bar']
        );
        $this->seedFileForMedia($media1);
        $this->seedFileForMedia($media2);

        $artisan->call('media:import', ['disk' => 'tmp', '--directory' => 'a/b']);

        $this->assertEquals("Imported 1 file(s).\n", $artisan->output());
        $this->assertEquals(['bar'], Media::pluck('filename')->toArray());
    }

    public function test_it_creates_media_for_unmatched_files_non_recursively()
    {
        $artisan = $this->getArtisan();
        $media1 = factory(Media::class)->make(
            ['disk' => 'tmp', 'directory' => 'a', 'filename' => 'foo']
        );
        $media2 = factory(Media::class)->make(
            ['disk' => 'tmp', 'directory' => 'a/b', 'filename' => 'bar']
        );
        $this->seedFileForMedia($media1);
        $this->seedFileForMedia($media2);

        $artisan->call(
            'media:import',
            ['disk' => 'tmp', '--directory' => 'a', '--non-recursive' => true]
        );

        $this->assertEquals("Imported 1 file(s).\n", $artisan->output());
        $this->assertEquals(['foo'], Media::pluck('filename')->toArray());
    }

    public function test_it_skips_files_of_unmatched_aggregate_type()
    {
        $artisan = $this->getArtisan();
        $filesystem = app(FilesystemManager::class);
        /** @var \Plank\Mediable\MediaUploader $uploader */
        $uploader = app('mediable.uploader');
        $uploader->setAllowUnrecognizedTypes(false);
        $uploader->setAllowedAggregateTypes(['image']);
        $command = new ImportMediaCommand($filesystem, $uploader);

        $media = factory(Media::class)->make(
            ['disk' => 'tmp', 'extension' => 'foo', 'mime_type' => 'bar']
        );
        $this->seedFileForMedia($media, 'foo');

        $artisan->registerCommand($command);

        $artisan->call('media:import', ['disk' => 'tmp']);
        $this->assertEquals(
            "Imported 0 file(s).\nSkipped 1 file(s).\n",
            $artisan->output()
        );
    }

    public function test_it_updates_existing_media()
    {
        $artisan = $this->getArtisan();
        $media1 = factory(Media::class)->create(
            [
                'disk' => 'tmp',
                'filename' => 'foo',
                'extension' => 'png',
                'mime_type' => 'image/png',
                'aggregate_type' => 'foo'
            ]
        );
        $media2 = factory(Media::class)->create(
            [
                'disk' => 'tmp',
                'filename' => 'bar',
                'extension' => 'png',
                'size' => 7173,
                'mime_type' => 'image/png',
                'aggregate_type' => 'image'
            ]
        );
        $this->seedFileForMedia($media1, $this->sampleFile());
        $this->seedFileForMedia($media2, $this->sampleFile());

        $artisan->call('media:import', ['disk' => 'tmp', '--force' => true]);
        $this->assertEquals(
            ['image', 'image'],
            Media::pluck('aggregate_type')->toArray()
        );
        $this->assertEquals(
            "Imported 0 file(s).\nUpdated 1 record(s).\nSkipped 1 file(s).\n",
            $artisan->output()
        );
    }

    protected function getArtisan()
    {
        return app(Artisan::class);
    }
}
