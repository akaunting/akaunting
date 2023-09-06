<?php

namespace Plank\Mediable\Tests\Integration;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Plank\Mediable\Exceptions\MediaMoveException;
use Plank\Mediable\Exceptions\MediaUrlException;
use Plank\Mediable\Media;
use Plank\Mediable\Tests\Mocks\MediaSoftDelete;
use Plank\Mediable\Tests\Mocks\SampleMediable;
use Plank\Mediable\Tests\Mocks\SampleMediableSoftDelete;
use Plank\Mediable\Tests\TestCase;
use Plank\Mediable\UrlGenerators\TemporaryUrlGeneratorInterface;
use Plank\Mediable\UrlGenerators\UrlGeneratorFactory;
use Plank\Mediable\UrlGenerators\UrlGeneratorInterface;

class MediaTest extends TestCase
{
    public function test_it_has_path_accessors()
    {
        $media = $this->makeMedia(
            [
                'disk' => 'tmp',
                'directory' => 'a/b/c',
                'filename' => 'foo.bar',
                'extension' => 'jpg',
            ]
        );

        $this->assertEquals(
            storage_path('tmp/a/b/c/foo.bar.jpg'),
            $media->getAbsolutePath()
        );
        $this->assertEquals('a/b/c/foo.bar.jpg', $media->getDiskPath());
        $this->assertEquals('a/b/c', $media->directory);
        $this->assertEquals('foo.bar.jpg', $media->basename);
        $this->assertEquals('foo.bar', $media->filename);
        $this->assertEquals('jpg', $media->extension);
    }

    public function test_it_can_be_queried_by_directory()
    {
        $this->useDatabase();

        $this->createMedia(['directory' => 'foo']);
        $this->createMedia(['directory' => 'foo']);
        $this->createMedia(['directory' => 'bar']);
        $this->createMedia(['directory' => 'foo/baz']);

        $this->assertEquals(2, Media::inDirectory('tmp', 'foo')->count());
        $this->assertEquals(1, Media::inDirectory('tmp', 'foo/baz')->count());
    }

    public function test_it_can_be_queried_by_directory_recursively()
    {
        $this->useDatabase();

        $this->createMedia(['directory' => 'foo']);
        $this->createMedia(['directory' => 'foo/bar']);
        $this->createMedia(['directory' => 'foo/bar']);
        $this->createMedia(['directory' => 'foo/bar/baz']);

        $this->assertEquals(4, Media::inDirectory('tmp', 'foo', true)->count());
        $this->assertEquals(3, Media::inOrUnderDirectory('tmp', 'foo/bar')->count());
        $this->assertEquals(1, Media::inDirectory('tmp', 'foo/bar/baz', true)->count());
    }

    public function test_it_can_be_queried_by_basename()
    {
        $this->useDatabase();

        $this->createMedia(['filename' => 'foo', 'extension' => 'bar']);
        $this->createMedia(['id' => 99, 'filename' => 'baz', 'extension' => 'bat']);
        $this->createMedia(['filename' => 'bar', 'extension' => 'foo']);

        $this->assertEquals(99, Media::whereBasename('baz.bat')->first()->id);
    }

    public function test_it_can_be_queried_by_path_on_disk()
    {
        $this->useDatabase();

        $this->createMedia(
            [
                'id' => 4,
                'disk' => 'tmp',
                'directory' => 'foo/bar/baz',
                'filename' => 'bat',
                'extension' => 'jpg'
            ]
        );
        $this->assertEquals(
            4,
            Media::forPathOnDisk('tmp', 'foo/bar/baz/bat.jpg')->first()->id
        );
    }

    public function test_it_can_be_queried_by_path_on_disk_when_directory_is_empty()
    {
        $this->useDatabase();

        $this->createMedia(
            [
                'id' => 4,
                'disk' => 'tmp',
                'directory' => '',
                'filename' => 'bat',
                'extension' => 'jpg'
            ]
        );
        $this->assertEquals(4, Media::forPathOnDisk('tmp', 'bat.jpg')->first()->id);
    }

    public function test_it_can_view_human_readable_file_size()
    {
        $media = $this->makeMedia(['size' => 0]);

        $this->assertEquals('0 B', $media->readableSize());

        $media->size = 1024 * 1024;
        $this->assertEquals('1 MB', $media->readableSize(0));

        $media->size = 1024 * 1024 + 1024 * 100;
        $this->assertEquals('1.1 MB', $media->readableSize(2));
    }

    public function test_it_can_be_checked_for_public_visibility()
    {
        $this->useFilesystem('tmp');
        $this->useFilesystem('uploads');

        $media = $this->makeMedia(['disk' => 'tmp']);
        $this->seedFileForMedia($media);
        $this->assertFalse($media->isPubliclyAccessible());

        $media = $this->makeMedia(['disk' => 'uploads']);
        $this->seedFileForMedia($media);
        $this->assertTrue($media->isPubliclyAccessible());

        $media->makePrivate();
        $this->assertFalse($media->isPubliclyAccessible());
        $media->makePublic();
        $this->assertTrue($media->isPubliclyAccessible());
    }

    public function test_it_can_be_checked_for_public_visibility_s3()
    {
        if (!$this->s3ConfigLoaded()) {
            $this->markTestSkipped('S3 Credentials not available.');
        }

        $this->useFilesystem('s3');

        $media = $this->makeMedia(['disk' => 's3']);
        $this->seedFileForMedia($media);
        try {
            $this->assertTrue($media->isPubliclyAccessible());

            $media->makePrivate();
            $this->assertFalse($media->isPubliclyAccessible());

            $media->makePublic();
            $this->assertTrue($media->isPubliclyAccessible());

            config()->set('filesystems.disks.s3.visibility', 'hidden');
            $this->assertFalse($media->isPubliclyAccessible());
        } finally {
            app('filesystem')->disk($media->disk)->delete($media->getDiskPath());
        }
    }

    public function test_it_can_generate_a_url_to_the_local_file()
    {
        $media = $this->makeMedia(
            [
                'disk' => 'uploads',
                'directory' => 'foo/bar',
                'filename' => 'baz',
                'extension' => 'jpg'
            ]
        );
        $this->seedFileForMedia($media);
        $this->assertEquals('http://localhost/uploads/foo/bar/baz.jpg', $media->getUrl());
    }

    public function test_it_can_generate_a_custom_url_to_the_local_file()
    {
        $this->app['config']->set('filesystems.disks.uploads.url', 'http://example.com');
        $media = $this->makeMedia(
            [
                'disk' => 'uploads',
                'directory' => 'foo/bar',
                'filename' => 'baz',
                'extension' => 'jpg'
            ]
        );
        $this->seedFileForMedia($media);
        $this->assertEquals('http://example.com/foo/bar/baz.jpg', $media->getUrl());
    }

    public function test_it_can_generate_a_url_to_the_file_on_s3()
    {
        if (!$this->s3ConfigLoaded()) {
            $this->markTestSkipped('S3 Credentials not available.');
        }
        $media = $this->makeMedia(
            [
                'disk' => 's3',
                'directory' => 'foo/bar',
                'filename' => 'baz',
                'extension' => 'jpg'
            ]
        );
        $this->seedFileForMedia($media);
        try {
            $this->assertEquals(
                sprintf(
                    'https://%s.s3.%s.amazonaws.com/%s/foo/bar/baz.jpg',
                    env('S3_BUCKET'),
                    env('S3_REGION'),
                    config('filesystems.disks.s3.root')
                ),
                $media->getUrl()
            );
        } finally {
            app('filesystem')->disk($media->disk)->delete($media->getDiskPath());
        }
    }

    public function test_it_can_check_if_its_file_exists()
    {
        $this->useFilesystem('tmp');

        $media = $this->makeMedia(['disk' => 'tmp']);
        $this->assertFalse($media->fileExists());
        $this->seedFileForMedia($media);
        $this->assertTrue($media->fileExists());
    }

    public function test_it_can_be_moved_on_disk()
    {
        $this->useFilesystem('tmp');
        $this->useDatabase();

        $media = $this->makeMedia(
            [
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar',
                'extension' => 'baz'
            ]
        );
        $this->seedFileForMedia($media);

        $media->move('alpha/beta');
        $this->assertEquals('alpha/beta/bar.baz', $media->getDiskPath());
        $this->assertTrue($media->fileExists());
        $media->move('', 'gamma.baz');
        $this->assertEquals('gamma.baz', $media->getDiskPath());
        $media->rename('foo.bar');
        $this->assertEquals('foo.bar.baz', $media->getDiskPath());
        $this->assertTrue($media->fileExists());
    }

    public function test_it_can_be_copied_on_disk()
    {
        $this->useFilesystem('tmp');
        $this->useDatabase();

        $media = $this->makeMedia(
            [
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar',
                'extension' => 'baz'
            ]
        );
        $this->seedFileForMedia($media);

        // copy the file and make some checks
        $copiedMedia1 = $media->copyTo('alpha', 'test');
        $this->assertEquals('alpha/test.baz', $copiedMedia1->getDiskPath());
        $this->assertTrue($copiedMedia1->fileExists());

        // check that the "old" media file still exists
        $this->assertEquals('foo/bar.baz', $media->getDiskPath());
        $this->assertTrue($media->fileExists());

        // check if it works without a filename (the filename of the original media object is used!)
        $copiedMedia2 = $media->copyTo('beta');
        $this->assertEquals('beta/bar.baz', $copiedMedia2->getDiskPath());
        $this->assertTrue($copiedMedia2->fileExists());

        // check if it throws an exception if the file already exists
        $this->expectException(MediaMoveException::class);
        $media->copyTo('alpha', 'test');
    }

    public function test_it_throws_an_exception_if_moving_to_existing_file()
    {
        $this->useFilesystem('tmp');

        $media1 = $this->makeMedia(
            [
                'disk' => 'tmp',
                'directory' => '',
                'filename' => 'foo',
                'extension' => 'baz'
            ]
        );
        $media2 = $this->makeMedia(
            [
                'disk' => 'tmp',
                'directory' => '',
                'filename' => 'bar',
                'extension' => 'baz'
            ]
        );
        $this->seedFileForMedia($media1);
        $this->seedFileForMedia($media2);

        $this->expectException(MediaMoveException::class);
        $media1->move('', 'bar.baz');
    }

    public function test_it_can_be_moved_to_another_disk_public()
    {
        $this->useFilesystem('tmp');
        $this->useFilesystem('uploads');

        $this->useDatabase();

        $media = $this->makeMedia(
            [
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar',
                'extension' => 'baz'
            ]
        );
        $original_path = $media->getAbsolutePath();
        $this->seedFileForMedia($media);
        $media->makePublic();

        $media->moveToDisk('uploads', 'alpha/beta', 'gamma');
        $this->assertEquals('uploads', $media->disk);
        $this->assertEquals('alpha/beta/gamma.baz', $media->getDiskPath());
        $this->assertTrue($media->fileExists());
        $this->assertFalse(file_exists($original_path));
        $this->assertTrue($media->isVisible());
    }

    public function test_it_can_be_moved_to_another_disk_private()
    {
        $this->useFilesystem('tmp');
        $this->useFilesystem('uploads');

        $this->useDatabase();

        $media = $this->makeMedia(
            [
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar',
                'extension' => 'baz'
            ]
        );
        $original_path = $media->getAbsolutePath();
        $this->seedFileForMedia($media);
        $media->makePrivate();

        $media->moveToDisk('uploads', 'alpha/beta', 'gamma');
        $this->assertEquals('uploads', $media->disk);
        $this->assertEquals('alpha/beta/gamma.baz', $media->getDiskPath());
        $this->assertTrue($media->fileExists());
        $this->assertFalse(file_exists($original_path));
        $this->assertFalse($media->isVisible());
    }

    public function test_it_can_be_copied_to_another_disk_public()
    {
        $this->useFilesystem('tmp');
        $this->useFilesystem('uploads');

        $this->useDatabase();

        $media = $this->makeMedia(
            [
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar',
                'extension' => 'baz'
            ]
        );
        $this->seedFileForMedia($media);
        $media->makePublic();

        $newMedia = $media->copyToDisk('uploads', 'alpha/beta', 'gamma');
        $this->assertEquals('uploads', $newMedia->disk);
        $this->assertEquals('alpha/beta/gamma.baz', $newMedia->getDiskPath());
        $this->assertTrue($newMedia->fileExists());
        $this->assertTrue($newMedia->isVisible());

        //original should be unchanged
        $this->assertEquals('tmp', $media->disk);
        $this->assertEquals('foo/bar.baz', $media->getDiskPath());
        $this->assertTrue($media->fileExists());
        $this->assertTrue($media->isVisible());
    }

    public function test_it_can_be_copied_to_another_disk_private()
    {
        $this->useFilesystem('tmp');
        $this->useFilesystem('uploads');

        $this->useDatabase();

        $media = $this->makeMedia(
            [
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar',
                'extension' => 'baz'
            ]
        );
        $this->seedFileForMedia($media);
        $media->makePrivate();

        $newMedia = $media->copyToDisk('uploads', 'alpha/beta', 'gamma');
        $this->assertEquals('uploads', $newMedia->disk);
        $this->assertEquals('alpha/beta/gamma.baz', $newMedia->getDiskPath());
        $this->assertTrue($newMedia->fileExists());
        $this->assertFalse($newMedia->isVisible());

        //original should be unchanged
        $this->assertEquals('tmp', $media->disk);
        $this->assertEquals('foo/bar.baz', $media->getDiskPath());
        $this->assertTrue($media->fileExists());
        $this->assertFalse($media->isVisible());
    }

    public function test_it_can_access_file_contents()
    {
        $this->useFilesystem('tmp');

        $media = $this->makeMedia(
            [
                'disk' => 'tmp',
                'extension' => 'html'
            ]
        );
        $this->seedFileForMedia($media, '<h1>Hello World</h1>');
        $this->assertEquals('<h1>Hello World</h1>', $media->contents());
    }

    public function test_it_deletes_its_file_on_deletion()
    {
        $this->useDatabase();
        $this->useFilesystem('tmp');

        $media = $this->createMedia(
            [
                'disk' => 'tmp',
                'directory' => '',
                'filename' => 'file',
                'extension' => 'txt'
            ]
        );
        $this->seedFileForMedia($media);
        $path = $media->getAbsolutePath();

        $this->assertFileExists($path);
        $media->delete();
        $this->assertFalse(file_exists($path));
    }

    public function test_it_cascades_relationship_on_delete()
    {
        $this->useDatabase();

        $media = $this->createMedia();
        $mediable = factory(SampleMediable::class)->create();
        $mediable->attachMedia($media, 'foo');

        $media->delete();
        $this->assertEquals(0, $mediable->getMedia('foo')->count());
    }

    public function test_it_doesnt_cascade_relationship_on_soft_delete()
    {
        $this->useDatabase();

        $media = factory(MediaSoftDelete::class)->create();
        $mediable = factory(SampleMediable::class)->create();
        $mediable->attachMedia($media, 'foo');

        $media->delete();
        $this->assertEquals(1, $mediable->getMedia('foo')->count());
    }

    public function test_it_cascades_relationships_on_soft_delete_with_config()
    {
        $this->useDatabase();

        $mediable = factory(SampleMediable::class)->create();
        $media = factory(MediaSoftDelete::class)->create();
        $mediable->attachMedia($media, 'foo');

        config()->set('mediable.detach_on_soft_delete', true);

        $media->delete();
        $this->assertEquals(0, $mediable->getMedia('foo')->count());
    }

    public function test_it_cascades_relationship_on_force_delete()
    {
        $this->useDatabase();

        $mediable = factory(SampleMediableSoftDelete::class)->create();
        $media = $this->createMedia();
        $mediable->attachMedia($media, 'foo');

        $media->forceDelete();
        $this->assertEquals(0, $mediable->getMedia('foo')->count());
    }

    public function test_it_retrieves_models_via_custom_mediables_table()
    {
        $this->useDatabase();

        config()->set('mediable.mediables_table', 'prefixed_mediables');

        $media = $this->createMedia();
        $mediable = factory(SampleMediable::class)->create();
        $mediable->attachMedia($media, 'foo');

        $this->assertEmpty(DB::table('mediables')->get());
        $this->assertCount(1, $media->models(SampleMediable::class)->get());
    }

    public function test_it_cascades_relationships_on_soft_delete_with_config_via_custom_mediables_table(
    ) {
        $this->useDatabase();

        config()->set('mediable.mediables_table', 'prefixed_mediables');
        config()->set('mediable.detach_on_soft_delete', true);

        $media = factory(MediaSoftDelete::class)->create();
        $mediable = factory(SampleMediableSoftDelete::class)->create();
        $mediable->attachMedia($media, 'foo');

        $this->assertNotEmpty(DB::table('prefixed_mediables')->get());
        $media->delete();
        $this->assertEmpty(DB::table('prefixed_mediables')->get());
    }

    public function test_it_can_stream_contents()
    {
        $this->useFilesystem('tmp');

        $media = $this->makeMedia(
            [
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar',
                'extension' => 'baz'
            ]
        );
        $this->seedFileForMedia($media, 'test');

        $stream = $media->stream();

        $this->assertEquals('test', $stream->getContents());
    }

    public function test_it_can_detect_variant_status()
    {
        $media = $this->makeMedia(
            [
                'original_media_id' => null,
                'variant_name' => null
            ]
        );

        $this->assertTrue($media->isOriginal());
        $this->assertFalse($media->isVariant());
        $this->assertFalse($media->isVariant('foo'));

        $media->original_media_id = 99;
        $media->variant_name = 'foo';

        $this->assertFalse($media->isOriginal());
        $this->assertTrue($media->isVariant());
        $this->assertTrue($media->isVariant('foo'));
        $this->assertFalse($media->isVariant('bar'));

        $result = $media->makeOriginal();

        $this->assertSame($result, $media);
        $this->assertTrue($media->isOriginal());
        $this->assertFalse($media->isVariant());
        $this->assertNull($media->original_media_id);
        $this->assertNull($media->variant_name);
        $this->assertFalse($media->isVariant('foo'));
    }

    public function test_it_can_be_made_a_variant_of_another()
    {
        $this->useDatabase();

        $media1 = $this->createMedia(
            [
                'original_media_id' => null,
                'variant_name' => null
            ]
        );

        $media2 = $this->createMedia(
            [
                'original_media_id' => null,
                'variant_name' => null
            ]
        );

        $media2->makeVariantOf($media1->getKey(), 'foo');

        $this->assertEquals($media1->getKey(), $media2->original_media_id);
        $this->assertEquals('foo', $media2->variant_name);

        $media2->makeOriginal();
        $media1->original_media_id = 99;

        $media2->makeVariantOf($media1, 'foo');

        $this->assertEquals(99, $media2->original_media_id);
        $this->assertEquals('foo', $media2->variant_name);
    }

    public function test_it_throws_if_cant_find_new_original()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->useDatabase();

        $media = $this->makeMedia(
            [
                'original_media_id' => null,
                'variant_name' => null
            ]
        );

        $media->makeVariantOf(9999, 'not_found');
    }

    public function test_it_can_be_queried_by_variant_status()
    {
        $this->useDatabase();
        $media1 = $this->createMedia(
            [
                'original_media_id' => null,
                'variant_name' => null
            ]
        );

        $media2 = $this->createMedia(
            [
                'original_media_id' => $media1->getKey(),
                'variant_name' => 'foo'
            ]
        );

        $media3 = $this->createMedia(
            [
                'original_media_id' => $media1->getKey(),
                'variant_name' => 'bar'
            ]
        );

        $this->assertEquals(
            [$media1->getKey()],
            Media::whereIsOriginal()->get()->modelKeys()
        );

        $this->assertEquals(
            [$media2->getKey(), $media3->getKey()],
            Media::whereIsVariant()->orderBy('id')->get()->modelKeys()
        );

        $this->assertEquals(
            [$media3->getKey()],
            Media::whereIsVariant('bar')->get()->modelKeys()
        );
    }

    public function test_it_can_find_other_variants()
    {
        $media1 = $this->makeMedia(
            [
                'id' => 4,
                'original_media_id' => null,
                'variant_name' => null
            ]
        );

        $media2 = $this->makeMedia(
            [
                'id' => 5,
                'original_media_id' => $media1->getKey(),
                'variant_name' => 'foo'
            ]
        );

        $media3 = $this->makeMedia(
            [
                'id' => 6,
                'original_media_id' => $media1->getKey(),
                'variant_name' => 'bar'
            ]
        );

        $media1->setRelation('variants', new Collection([$media2, $media3]));
        $media2->setRelation('originalMedia', $media1);
        $media3->setRelation('originalMedia', $media1);

        $this->assertEquals($media1, $media1->findOriginal());
        $this->assertEquals($media1, $media2->findOriginal());
        $this->assertEquals($media1, $media3->findOriginal());

        $this->assertEquals($media2, $media1->findVariant('foo'));
        $this->assertEquals($media2, $media2->findVariant('foo'));
        $this->assertEquals($media2, $media3->findVariant('foo'));

        $this->assertEquals($media3, $media1->findVariant('bar'));
        $this->assertEquals($media3, $media2->findVariant('bar'));
        $this->assertEquals($media3, $media3->findVariant('bar'));

        $this->assertTrue($media1->hasVariant('foo'));
        $this->assertTrue($media2->hasVariant('foo'));
        $this->assertTrue($media3->hasVariant('foo'));
        $this->assertTrue($media1->hasVariant('bar'));
        $this->assertTrue($media2->hasVariant('bar'));
        $this->assertTrue($media3->hasVariant('bar'));
        $this->assertFalse($media1->hasVariant('original'));
        $this->assertFalse($media2->hasVariant('original'));
        $this->assertFalse($media3->hasVariant('original'));

        $this->assertEquals(
            new Collection(['foo' => $media2, 'bar' => $media3]),
            $media1->getAllVariants()
        );
        $this->assertEquals(
            new Collection(['original' => $media1, 'bar' => $media3]),
            $media2->getAllVariants()
        );
        $this->assertEquals(
            new Collection(['original' => $media1, 'foo' => $media2]),
            $media3->getAllVariants()
        );

        $all = new Collection(
            ['original' => $media1, 'foo' => $media2, 'bar' => $media3]
        );

        $this->assertEquals($all, $media1->getAllVariantsAndSelf());
        $this->assertEquals($all, $media2->getAllVariantsAndSelf());
        $this->assertEquals($all, $media3->getAllVariantsAndSelf());
    }

    public function test_it_generates_temporary_urls()
    {
        $media = $this->makeMedia();
        $expiry = Carbon::now();

        $generator = $this->createMock(
            TemporaryUrlGeneratorInterface::class
        );
        $generator->expects($this->once())
            ->method('getTemporaryUrl')
            ->with($expiry)
            ->willReturn($url = 'https://example.com/path');
        $factory = $this->createMock(UrlGeneratorFactory::class);
        $factory->expects($this->once())
            ->method('create')
            ->willReturn($generator);
        app()->instance('mediable.url.factory', $factory);

        $this->assertEquals($url, $media->getTemporaryUrl($expiry));
    }

    public function test_it_throws_for_unsupported_temporary_urls()
    {
        $this->expectException(MediaUrlException::class);
        $media = $this->makeMedia();

        $generator = $this->createMock(UrlGeneratorInterface::class);
        $factory = $this->createMock(UrlGeneratorFactory::class);
        $factory->expects($this->once())
            ->method('create')
            ->willReturn($generator);
        app()->instance('mediable.url.factory', $factory);

        $media->getTemporaryUrl(Carbon::now());
    }
}
