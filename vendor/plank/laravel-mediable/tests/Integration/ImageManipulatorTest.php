<?php

namespace Plank\Mediable\Tests\Integration;

use Illuminate\Filesystem\FilesystemManager;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Plank\Mediable\Exceptions\ImageManipulationException;
use Plank\Mediable\ImageManipulation;
use Plank\Mediable\ImageManipulator;
use Plank\Mediable\Media;
use Plank\Mediable\Tests\TestCase;

class ImageManipulatorTest extends TestCase
{
    public function test_it_can_be_accessed_via_facade()
    {
        $this->assertInstanceOf(
            ImageManipulator::class,
            \Plank\Mediable\Facades\ImageManipulator::getFacadeRoot()
        );
    }

    public function test_it_sets_and_has_variants()
    {
        $manipulator = $this->getManipulator();
        $this->assertFalse($manipulator->hasVariantDefinition('foo'));
        $manipulator->defineVariant(
            'foo',
            new ImageManipulation($this->getMockCallable())
        );
        $this->assertTrue($manipulator->hasVariantDefinition('foo'));
    }

    public function test_it_retrieves_all_variants()
    {
        $manipulator = $this->getManipulator();
        $variant = new ImageManipulation($this->getMockCallable());
        $this->assertEquals(collect(), $manipulator->getAllVariantDefinitions());
        $this->assertEquals([], $manipulator->getAllVariantNames());

        $manipulator->defineVariant(
            'foo',
            $variant
        );
        $this->assertEquals(
            collect(['foo' => $variant]),
            $manipulator->getAllVariantDefinitions()
        );
        $this->assertEquals(['foo'], $manipulator->getAllVariantNames());

        $manipulator->defineVariant(
            'bar',
            $variant
        );
        $this->assertEquals(
            collect(['foo' => $variant, 'bar' => $variant]),
            $manipulator->getAllVariantDefinitions()
        );
        $this->assertEquals(['foo', 'bar'], $manipulator->getAllVariantNames());
    }

    public function test_it_can_store_and_retrieve_by_tag()
    {
        $manipulator = $this->getManipulator();
        $variant = new ImageManipulation($this->getMockCallable());
        $manipulator->defineVariant(
            'foo',
            $variant,
            ['a', 'b']
        );
        $manipulator->defineVariant(
            'bar',
            $variant,
            ['b']
        );

        $this->assertEquals(
            collect(['foo' => $variant]),
            $manipulator->getVariantDefinitionsByTag('a')
        );
        $this->assertEquals(['foo'], $manipulator->getVariantNamesByTag('a'));
        $this->assertEquals(
            collect(['foo' => $variant, 'bar' => $variant]),
            $manipulator->getVariantDefinitionsByTag('b')
        );
        $this->assertEquals(['foo', 'bar'], $manipulator->getVariantNamesByTag('b'));
        $this->assertEquals(
            collect([]),
            $manipulator->getVariantDefinitionsByTag('c')
        );
        $this->assertEquals([], $manipulator->getVariantNamesByTag('c'));
    }

    public function test_it_throws_for_non_image_media()
    {
        $this->expectException(ImageManipulationException::class);
        $this->expectExceptionMessage(
            "Cannot manipulate media with an aggregate type other than 'image', got 'document'."
        );
        $this->getManipulator()->createImageVariant(
            $this->makeMedia(['aggregate_type' => 'document']),
            'variant'
        );
    }

    public function test_it_throws_for_unknown_variants()
    {
        $this->expectException(ImageManipulationException::class);
        $this->expectExceptionMessage("Unknown variant 'invalid'.");
        $this->getManipulator()->createImageVariant(
            $this->makeMedia(['aggregate_type' => 'image']),
            'invalid'
        );
    }

    public function test_it_throws_for_indeterminate_output_format()
    {
        $this->useFilesystem('tmp');
        $this->expectException(ImageManipulationException::class);
        $this->expectExceptionMessage("Unable to determine valid output format for file.");
        $manipulation = ImageManipulation::make(
            function (Image $image) {
            }
        );
        $media = $this->makeMedia(
            [
                'disk' => 'tmp',
                'filename' => 'foo',
                'extension' => 'psd',
                'mime_type' => 'image/vnd.adobe.photoshop',
                'aggregate_type' => 'image'
            ]
        );
        $this->seedFileForMedia($media);
        $manipulator = $this->getManipulator();
        $manipulator->defineVariant('foo', $manipulation);
        $manipulator->createImageVariant($media, 'foo');
    }

    public function test_it_can_create_a_variant()
    {
        $this->useFilesystem('tmp');
        $this->useDatabase();

        $media = $this->makeMedia(
            [
                'id' => 10,
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar',
                'extension' => 'png',
                'mime_type' => 'image/png',
                'aggregate_type' => 'image'
            ]
        );
        $this->seedFileForMedia($media, $this->sampleFile());

        $beforeSave = $this->getMockCallable();
        $beforeSave->expects($this->once())
            ->method('__invoke')
            ->with(
                $this->callback(
                    function (Media $media) {
                        $result = $media->directory === 'foo';
                        $media->directory = 'baz';
                        return $result;
                    }
                )
            );

        $manipulation = ImageManipulation::make(
            function (Image $image) {
                $image->resize(16, 16);
            }
        )->beforeSave($beforeSave);

        $imageManipulator = $this->getManipulator();
        $imageManipulator->defineVariant('test', $manipulation);
        $result = $imageManipulator->createImageVariant($media, 'test');

        $this->assertTrue($result->exists);
        $this->assertEquals('tmp', $result->disk);
        $this->assertEquals('baz', $result->directory);
        $this->assertEquals('bar-test', $result->filename);
        $this->assertEquals('png', $result->extension);
        $this->assertEquals('image/png', $result->mime_type);
        $this->assertEquals('image', $result->aggregate_type);
        $this->assertContains(
            $result->size,
            [
                449, // Laravel <=8
                438 // Laravel 9+
            ]
        );
        $this->assertEquals('test', $result->variant_name);
        $this->assertEquals(10, $result->original_media_id);
        $this->assertTrue($media->fileExists());
    }

    public function test_it_can_create_a_variant_of_a_variant()
    {
        $this->useFilesystem('tmp');
        $this->useDatabase();

        $originalMedia = $this->createMedia(['filename' => 'bar']);

        $media = $this->createMedia(
            [
                'id' => 20,
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar-other',
                'extension' => 'png',
                'mime_type' => 'image/png',
                'aggregate_type' => 'image',
                'variant_name' => 'other',
                'original_media_id' => $originalMedia->getKey()
            ]
        );
        $this->seedFileForMedia($media, $this->sampleFile());

        $manipulation = ImageManipulation::make(
            function (Image $image) {
                $image->resize(16, 16);
            }
        );

        $imageManipulator = $this->getManipulator();
        $imageManipulator->defineVariant('test', $manipulation);
        $result = $imageManipulator->createImageVariant($media, 'test');

        $this->assertEquals('bar-test', $result->filename);
        $this->assertEquals('test', $result->variant_name);
        $this->assertEquals($originalMedia->getKey(), $result->original_media_id);
        $this->assertTrue($media->fileExists());
    }

    public function formatProvider()
    {
        return [
            ['jpg', 'image/jpeg', 100],
            ['jpg', 'image/jpeg', 10],
            ['gif', 'image/gif', 90],
        ];
    }

    /**
     * @dataProvider formatProvider
     */
    public function test_it_can_create_a_variant_of_a_different_format(
        string $format,
        string $mime,
        int $quality
    ) {
        $this->useFilesystem('tmp');
        $this->useDatabase();

        $media = $this->makeMedia(
            [
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar',
                'extension' => 'png',
                'mime_type' => 'image/png',
                'aggregate_type' => 'image'
            ]
        );
        $this->seedFileForMedia($media, $this->sampleFile());

        $manipulation = ImageManipulation::make(
            function (Image $image) {
                $image->resize(16, 16);
            }
        )->setOutputFormat($format)->setOutputQuality($quality);

        $imageManipulator = $this->getManipulator();
        $imageManipulator->defineVariant('test', $manipulation);
        $result = $imageManipulator->createImageVariant($media, 'test');

        $this->assertEquals($format, $result->extension);
        $this->assertEquals($mime, $result->mime_type);
        $this->assertEquals('image', $result->aggregate_type);
        $this->assertTrue($media->fileExists());
    }

    public function test_it_can_output_to_custom_destination()
    {
        $this->useFilesystem('tmp');
        $this->useFilesystem('uploads');
        $this->useDatabase();

        $media = $this->makeMedia(
            [
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar',
                'extension' => 'png',
                'mime_type' => 'image/png',
                'aggregate_type' => 'image'
            ]
        );
        $this->seedFileForMedia($media, $this->sampleFile());

        $manipulation = ImageManipulation::make(
            function (Image $image) {
                $image->resize(16, 16);
            }
        )->toDestination('uploads', 'potato')->useFilename('onion');

        $imageManipulator = $this->getManipulator();
        $imageManipulator->defineVariant('test', $manipulation);
        $result = $imageManipulator->createImageVariant($media, 'test');

        $this->assertEquals('uploads', $result->disk);
        $this->assertEquals('potato', $result->directory);
        $this->assertEquals('onion', $result->filename);
        $this->assertTrue($media->fileExists());
    }

    public function test_it_can_output_to_hash_filename()
    {
        $this->useFilesystem('tmp');
        $this->useFilesystem('uploads');
        $this->useDatabase();

        $media = $this->makeMedia(
            [
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar',
                'extension' => 'png',
                'mime_type' => 'image/png',
                'aggregate_type' => 'image'
            ]
        );
        $this->seedFileForMedia($media, $this->sampleFile());

        $manipulation = ImageManipulation::make(
            function (Image $image) {
                $image->resize(16, 16);
            }
        )->toDisk('uploads')
            ->toDirectory('potato')
            ->useHashForFilename();

        $imageManipulator = $this->getManipulator();
        $imageManipulator->defineVariant('test', $manipulation);
        $result = $imageManipulator->createImageVariant($media, 'test');

        $this->assertEquals('uploads', $result->disk);
        $this->assertEquals('potato', $result->directory);
        $this->assertSame(1, preg_match('/^[a-f0-9]{32}$/', $result->filename));
        $this->assertTrue($media->fileExists());
    }

    public function test_it_errors_on_duplicate()
    {
        $this->expectException(ImageManipulationException::class);
        $this->useFilesystem('tmp');
        $this->useDatabase();

        $media = $this->makeMedia(
            [
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar-test',
                'extension' => 'png',
                'mime_type' => 'image/png',
                'aggregate_type' => 'image'
            ]
        );
        $this->seedFileForMedia($media, $this->sampleFile());
        $media->filename = 'bar';
        $this->seedFileForMedia($media, $this->sampleFile());

        $manipulation = ImageManipulation::make(
            function (Image $image) {
                $image->resize(16, 16);
            }
        )->onDuplicateError();

        $imageManipulator = $this->getManipulator();
        $imageManipulator->defineVariant('test', $manipulation);
        $imageManipulator->createImageVariant($media, 'test');
    }

    public function test_it_errors_on_duplicate_after_before_save()
    {
        $this->expectException(ImageManipulationException::class);
        $this->useFilesystem('tmp');
        $this->useDatabase();

        $media = $this->makeMedia(
            [
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar',
                'extension' => 'png',
                'mime_type' => 'image/png',
                'aggregate_type' => 'image'
            ]
        );
        $this->seedFileForMedia($media, $this->sampleFile());

        $manipulation = ImageManipulation::make(
            function (Image $image) {
                $image->resize(16, 16);
            }
        )->onDuplicateError()
            ->beforeSave(
                function (Media $variant) {
                    $variant->filename = 'bar';
                }
            );

        $imageManipulator = $this->getManipulator();
        $imageManipulator->defineVariant('test', $manipulation);
        $imageManipulator->createImageVariant($media, 'test');
    }

    public function test_it_increments_on_duplicate()
    {
        $this->useFilesystem('tmp');
        $this->useDatabase();

        $media = $this->makeMedia(
            [
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar-test',
                'extension' => 'png',
                'mime_type' => 'image/png',
                'aggregate_type' => 'image'
            ]
        );
        $this->seedFileForMedia($media, $this->sampleFile());
        $media->filename = 'bar-test-1';
        $this->seedFileForMedia($media, $this->sampleFile());
        $media->filename = 'bar';
        $this->seedFileForMedia($media, $this->sampleFile());

        $manipulation = ImageManipulation::make(
            function (Image $image) {
                $image->resize(16, 16);
            }
        )->onDuplicateIncrement();

        $imageManipulator = $this->getManipulator();
        $imageManipulator->defineVariant('test', $manipulation);
        $result = $imageManipulator->createImageVariant($media, 'test');

        $this->assertEquals('bar-test-2', $result->filename);
    }

    public function test_it_increments_on_duplicate_after_before_save()
    {
        $this->useFilesystem('tmp');
        $this->useDatabase();

        $media = $this->makeMedia(
            [
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar',
                'extension' => 'png',
                'mime_type' => 'image/png',
                'aggregate_type' => 'image'
            ]
        );
        $this->seedFileForMedia($media, $this->sampleFile());

        $manipulation = ImageManipulation::make(
            function (Image $image) {
                $image->resize(16, 16);
            }
        )->onDuplicateIncrement()
            ->beforeSave(
                function (Media $variant) {
                    $variant->filename = 'bar';
                }
            );

        $imageManipulator = $this->getManipulator();
        $imageManipulator->defineVariant('test', $manipulation);
        $result = $imageManipulator->createImageVariant($media, 'test');

        $this->assertEquals('bar-1', $result->filename);
    }

    public function test_it_skips_existing_variants()
    {
        $this->useFilesystem('tmp');
        $this->useDatabase();

        $originalMedia = $this->createMedia(
            [
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar',
                'aggregate_type' => 'image'
            ]
        );
        $previousVariant = $this->createMedia(
            [
                'disk' => 'uploads',
                'directory' => 'foo',
                'filename' => 'bar-test',
                'aggregate_type' => 'image',
                'variant_name' => 'test',
                'original_media_id' => $originalMedia->getKey()
            ]
        );

        $manipulation = ImageManipulation::make(
            function (Image $image) {
                $image->resize(16, 16);
            }
        );

        $imageManipulator = $this->getManipulator();
        $imageManipulator->defineVariant('test', $manipulation);

        // from original
        $result = $imageManipulator->createImageVariant($originalMedia, 'test');
        $this->assertEquals($previousVariant->getKey(), $result->getKey());
        $this->assertEquals($previousVariant->filename, $result->filename);
        $this->assertEquals($previousVariant->variant_name, $result->variant_name);
        $this->assertEquals(
            $previousVariant->original_media_id,
            $result->original_media_id
        );

        //from variant
        $result = $imageManipulator->createImageVariant($previousVariant, 'test');
        $this->assertEquals($previousVariant->getKey(), $result->getKey());
        $this->assertEquals($previousVariant->filename, $result->filename);
        $this->assertEquals($previousVariant->variant_name, $result->variant_name);
        $this->assertEquals(
            $previousVariant->original_media_id,
            $result->original_media_id
        );
    }

    public function test_it_can_recreate_an_existing_variant()
    {
        $this->useFilesystem('tmp');
        $this->useDatabase();

        $originalMedia = $this->createMedia(
            [
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar',
                'extension' => 'png',
                'mime_type' => 'image/png',
                'aggregate_type' => 'image'
            ]
        );
        $this->seedFileForMedia($originalMedia, $this->sampleFile());
        $previousVariant = $this->createMedia(
            [
                'disk' => 'uploads',
                'directory' => 'foo',
                'filename' => 'bar-test',
                'extension' => 'png',
                'mime_type' => 'image/png',
                'aggregate_type' => 'image',
                'variant_name' => 'test',
                'size' => 0,
                'original_media_id' => $originalMedia->getKey()
            ]
        );
        $this->seedFileForMedia($previousVariant);

        $manipulation = ImageManipulation::make(
            function (Image $image) {
                $image->resize(16, 16);
            }
        );

        $imageManipulator = $this->getManipulator();
        $imageManipulator->defineVariant('test', $manipulation);

        // from original
        $result = $imageManipulator->createImageVariant($originalMedia, 'test', true);
        $this->assertEquals($previousVariant->getKey(), $result->getKey());
        $this->assertGreaterThan(0, $result->size);

        $this->seedFileForMedia($previousVariant, $this->sampleFile());

        $result2 = $imageManipulator->createImageVariant($previousVariant, 'test', true);
        $this->assertEquals($previousVariant->getKey(), $result2->getKey());
        $this->assertEquals($result->size, $result2->size);
    }

    public function test_it_can_recreate_existing_variant_to_a_new_destination()
    {
        $this->useFilesystem('tmp');
        $this->useFilesystem('uploads');
        $this->useDatabase();

        $originalMedia = $this->createMedia(
            [
                'disk' => 'tmp',
                'directory' => 'foo',
                'filename' => 'bar',
                'extension' => 'png',
                'mime_type' => 'image/png',
                'aggregate_type' => 'image'
            ]
        );
        $this->seedFileForMedia($originalMedia, $this->sampleFile());
        $previousVariant = $this->createMedia(
            [
                'disk' => 'uploads',
                'directory' => 'foo',
                'filename' => 'bar-test',
                'extension' => 'png',
                'mime_type' => 'image/png',
                'aggregate_type' => 'image',
                'variant_name' => 'test',
                'size' => 0,
                'original_media_id' => $originalMedia->getKey()
            ]
        );
        $this->seedFileForMedia($previousVariant);

        $manipulation = ImageManipulation::make(
            function (Image $image) {
                $image->resize(16, 16);
            }
        )->toDestination('uploads', 'potato');

        $imageManipulator = $this->getManipulator();
        $imageManipulator->defineVariant('test', $manipulation);

        // from original
        $result = $imageManipulator->createImageVariant($originalMedia, 'test', true);
        $this->assertEquals($previousVariant->getKey(), $result->getKey());
        $this->assertEquals('uploads', $result->disk);
        $this->assertEquals('potato', $result->directory);
        $this->assertGreaterThan(0, $result->size);
        $this->assertTrue($result->fileExists());

        $this->assertFalse($previousVariant->fileExists());
    }

    public function visibilityProvider()
    {
        return [
            ['uploads', 'public', null, true],
            ['uploads', 'private', null, true],
            ['tmp', 'public', null, false],
            ['tmp', 'private', null, false],
            ['tmp', 'public', 'match', true],
            ['tmp', 'private', 'match', false],
            ['tmp', 'public', 'private', false],
            ['tmp', 'private', 'public', true]
        ];
    }

    /** @dataProvider visibilityProvider */
    public function test_variant_created_with_visibility(
        string $disk,
        string $originalVisibility,
        ?string $manipulationVisibility,
        bool $expectedVisibility
    ) {
        $this->useFilesystem($disk);
        $this->useDatabase();

        $media = $this->makeMedia(
            [
                'disk' => $disk,
                'directory' => 'foo',
                'filename' => 'bar',
                'extension' => 'png',
                'mime_type' => 'image/png',
                'aggregate_type' => 'image'
            ]
        );
        $this->seedFileForMedia($media, $this->sampleFile());
        $originalVisibility == 'public' ? $media->makePublic() : $media->makePrivate();

        $manipulation = ImageManipulation::make($this->getMockCallable())
            ->setVisibility($manipulationVisibility)
            ->toDisk($disk);

        $imageManipulator = $this->getManipulator();
        $imageManipulator->defineVariant('test', $manipulation);

        $result = $imageManipulator->createImageVariant($media, 'test', true);
        $this->assertSame($expectedVisibility, $result->isVisible());
    }

    public function getManipulator(): ImageManipulator
    {
        return new ImageManipulator(
            new ImageManager(['driver' => 'gd']),
            app(FilesystemManager::class)
        );
    }
}
