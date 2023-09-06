<?php

namespace Plank\Mediable\Tests\Integration;

use Illuminate\Support\Facades\DB;
use Plank\Mediable\Media;
use Plank\Mediable\MediableCollection;
use Plank\Mediable\Tests\Mocks\SampleMediable;
use Plank\Mediable\Tests\Mocks\SampleMediableSoftDelete;
use Plank\Mediable\Tests\TestCase;

class MediableTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->useDatabase();
    }

    public function test_it_can_attach_and_retrieve_media_by_a_tag()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 2]);

        $mediable->attachMedia($media1, 'foo');
        $result = $mediable->getMedia('foo');

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
        $this->assertEquals([2], $result->pluck('id')->toArray());
    }

    public function test_it_can_attach_to_numeric_tags()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 2]);

        $mediable->attachMedia($media1, (string)2018);
        $result = $mediable->getMedia((string)2018);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
        $this->assertEquals([2], $result->pluck('id')->toArray());
    }

    public function test_it_can_attach_one_media_to_multiple_tags()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 2]);

        $mediable->attachMedia($media1, 'bar');
        $mediable->attachMedia($media1, 'foo');

        $this->assertEquals([2], $mediable->getMedia('foo')->pluck('id')->toArray());
        $this->assertEquals([2], $mediable->getMedia('bar')->pluck('id')->toArray());
        $this->assertEquals(
            0,
            $mediable->getMedia('baz')->count(),
            'Found media for non-existent tag'
        );
    }

    public function test_it_can_attach_multiple_media_to_multiple_tags_simultaneously()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create();
        $media2 = factory(Media::class)->create();

        $mediable->attachMedia([$media1->id, $media2->id], ['foo', 'bar']);

        $this->assertCount(2, $mediable->getMedia('foo'));
        $this->assertCount(2, $mediable->getMedia('bar'));
    }

    public function test_it_can_find_the_first_media()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);

        $mediable->attachMedia($media1, 'foo');
        $mediable->attachMedia($media2, 'foo');

        $this->assertEquals(1, $mediable->firstMedia('foo')->id);
    }

    public function test_it_can_find_the_last_media()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);

        $mediable->attachMedia($media1, 'foo');
        $mediable->attachMedia($media2, 'foo');

        $this->assertEquals(2, $mediable->lastMedia('foo')->id);
    }

    public function test_it_can_find_media_matching_any_tags()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);
        $media3 = factory(Media::class)->create(['id' => 3]);

        $mediable->attachMedia($media1, 'foo');
        $mediable->attachMedia($media1, 'bar');
        $mediable->attachMedia($media2, 'bar');
        $mediable->attachMedia($media3, 'baz');

        $this->assertEquals(
            [1, 2],
            $mediable->getMedia(['foo', 'bar'], false)->pluck(
                'id'
            )->toArray()
        );
    }

    public function test_it_can_find_media_matching_multiple_tags()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);
        $media3 = factory(Media::class)->create(['id' => 3]);

        $mediable->attachMedia($media1, 'foo');
        $mediable->attachMedia($media1, 'bar');
        $mediable->attachMedia($media2, 'bar');
        $mediable->attachMedia($media3, 'baz');

        $this->assertEquals(
            [1],
            $mediable->getMedia(['foo', 'bar'], true)->pluck(
                'id'
            )->toArray()
        );
        $this->assertEquals(0, $mediable->getMedia(['foo', 'bat'], true)->count());
    }

    public function test_it_can_check_presence_of_attached_media()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);

        $mediable->attachMedia($media1, 'foo');
        $mediable->attachMedia($media2, 'foo');
        $mediable->attachMedia($media2, 'bar');

        $this->assertTrue($mediable->hasMedia('foo'));
        $this->assertTrue($mediable->hasMedia('bar'));
        $this->assertFalse($mediable->hasMedia('baz'));
        $this->assertTrue(
            $mediable->hasMedia(['bar', 'baz'], false),
            'Failed to find model matching one of many tag'
        );
        $this->assertFalse(
            $mediable->hasMedia(['bar', 'baz'], true),
            'Failed to match all tags'
        );
    }

    public function test_it_can_list_media_by_tag()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);

        $mediable->attachMedia($media1, 'foo');
        $mediable->attachMedia($media2, 'bar');

        $result = $mediable->getAllMediaByTag();
        $this->assertEquals(['foo', 'bar'], $result->keys()->toArray());
        $this->assertEquals([1], $result['foo']->pluck('id')->toArray());
        $this->assertEquals([2], $result['bar']->pluck('id')->toArray());
    }

    public function test_it_can_detach_media_by_tag()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media = factory(Media::class)->create();
        $mediable->attachMedia($media, 'foo');
        $mediable->detachMedia($media, 'foo');

        $this->assertEquals(0, $mediable->getMedia('foo')->count());
    }

    public function test_it_can_detach_media_of_multiple_tags()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media = factory(Media::class)->create(['id' => 1]);
        $mediable->attachMedia($media, 'foo');
        $mediable->attachMedia($media, 'bar');

        $mediable->detachMedia($media, ['foo', 'bar']);

        $this->assertEquals(0, $mediable->getMedia('foo')->count());
        $this->assertEquals(0, $mediable->getMedia('bar')->count());
    }

    public function test_it_can_sync_media_by_tag()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 2]);
        $media2 = factory(Media::class)->create(['id' => 3]);

        $mediable->attachMedia([$media1->id, $media2->id], 'foo');
        $mediable->attachMedia($media2, 'bar');
        $mediable->syncMedia($media1, 'foo');

        $this->assertEquals(1, $mediable->getMedia('foo')->count());
        $this->assertEquals(
            [3],
            $mediable->getMedia('bar')->pluck('id')->toArray(),
            'Modified other tags'
        );
    }

    public function test_it_can_sync_media_to_multiple_tags()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);
        $media3 = factory(Media::class)->create(['id' => 3]);

        $mediable->attachMedia($media1, 'foo');
        $mediable->attachMedia($media1, 'bar');

        $mediable->syncMedia([$media2->id, $media3->id], ['bar', 'baz']);

        $this->assertEquals([1], $mediable->getMedia('foo')->pluck('id')->toArray());
        $this->assertEquals([2, 3], $mediable->getMedia('bar')->pluck('id')->toArray());
        $this->assertEquals([2, 3], $mediable->getMedia('baz')->pluck('id')->toArray());
    }

    public function test_it_can_be_queried_by_any_media()
    {
        $mediable = factory(SampleMediable::class)->create();
        $mediable2 = factory(SampleMediable::class)->create();
        $media = factory(Media::class)->create();
        $mediable->attachMedia($media, 'foo');

        $result = SampleMediable::whereHasMedia([], false)->get();
        $this->assertEquals([$mediable->getKey()], $result->modelKeys());
    }

    public function test_it_can_be_queried_by_tag()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media = factory(Media::class)->create();
        $mediable->attachMedia($media, 'foo');

        $this->assertEquals(1, SampleMediable::whereHasMedia('foo', false)->count());
        $this->assertEquals(
            0,
            SampleMediable::whereHasMedia('bar', false)->count(),
            'Queriable by non-existent group'
        );
    }

    public function test_it_can_be_queried_by_tag_matching_all()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);

        $mediable->attachMedia($media1, ['foo', 'bar']);
        $mediable->attachMedia($media2, ['foo']);

        $this->assertEquals(
            [1],
            SampleMediable::whereHasMediaMatchAll(['foo', 'bar'])->get()->pluck(
                'id'
            )->toArray()
        );
        $this->assertEquals(
            [1],
            SampleMediable::whereHasMedia(['foo', 'bar'], true)->get()->pluck(
                'id'
            )->toArray()
        );
    }

    public function test_it_can_list_the_tags_a_media_is_attached_to()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media = factory(Media::class)->create();

        $mediable->attachMedia($media, 'foo');
        $mediable->attachMedia($media, 'bar');

        $this->assertContains('foo', $mediable->getTagsForMedia($media));
        $this->assertContains('bar', $mediable->getTagsForMedia($media));
    }

    public function test_it_can_disable_automatic_rehydration()
    {
        $mediable = factory(SampleMediable::class)->create();
        $mediable->rehydrates_media = false;
        $media = factory(Media::class)->create();

        $mediable->media;
        $mediable->attachMedia($media, 'foo');
        $this->assertEquals(0, $mediable->getMedia('foo')->count());
    }

    public function test_it_can_eager_load_media()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media = factory(Media::class)->create();
        $mediable->attachMedia($media, 'foo');

        $result = SampleMediable::withMedia()->first();
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertFalse($result->media[0]->relationLoaded('originalMedia'));
        $this->assertFalse($result->media[0]->relationLoaded('variants'));
    }

    public function test_it_can_eager_load_media_by_tag()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);
        $mediable->attachMedia($media1, 'foo');
        $mediable->attachMedia($media2, 'bar');

        $result = SampleMediable::withMedia(['bar'])->first();

        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2], $result->media->pluck('id')->toArray());
        $this->assertFalse($result->media[0]->relationLoaded('originalMedia'));
        $this->assertFalse($result->media[0]->relationLoaded('variants'));
    }

    public function test_it_can_eager_load_media_by_tag_matching_all()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);
        $mediable->attachMedia($media1, 'foo');
        $mediable->attachMedia($media2, ['bar', 'foo', 'baz']);

        $result = SampleMediable::withMedia(['bar', 'foo'], true)->first();
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2, 2], $result->media->pluck('id')->toArray());
        $this->assertFalse($result->media[0]->relationLoaded('originalMedia'));
        $this->assertFalse($result->media[0]->relationLoaded('variants'));

        $result = SampleMediable::withMediaMatchAll(['bar', 'foo'])->first();
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2, 2], $result->media->pluck('id')->toArray());
        $this->assertFalse($result->media[0]->relationLoaded('originalMedia'));
        $this->assertFalse($result->media[0]->relationLoaded('variants'));
    }

    public function test_it_can_eager_load_media_with_variants()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media = factory(Media::class)->create();
        $mediable->attachMedia($media, 'foo');

        $result = SampleMediable::withMedia([], false, true)->first();
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertTrue($result->media[0]->relationLoaded('originalMedia'));
        $this->assertTrue($result->media[0]->relationLoaded('variants'));

        $result = SampleMediable::withMediaAndVariants()->first();
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertTrue($result->media[0]->relationLoaded('originalMedia'));
        $this->assertTrue($result->media[0]->relationLoaded('variants'));
    }

    public function test_it_can_eager_load_media_with_variants_by_tag()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);
        $mediable->attachMedia($media1, 'foo');
        $mediable->attachMedia($media2, 'bar');

        $result = SampleMediable::withMedia(['bar'], false, true)->first();
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2], $result->media->pluck('id')->toArray());
        $this->assertTrue($result->media[0]->relationLoaded('originalMedia'));
        $this->assertTrue($result->media[0]->relationLoaded('variants'));

        $result = SampleMediable::withMediaAndVariants(['bar'])->first();
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2], $result->media->pluck('id')->toArray());
        $this->assertTrue($result->media[0]->relationLoaded('originalMedia'));
        $this->assertTrue($result->media[0]->relationLoaded('variants'));
    }

    public function test_it_can_eager_load_media_with_variants_by_tag_matching_all()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);
        $mediable->attachMedia($media1, 'foo');
        $mediable->attachMedia($media2, ['bar', 'foo', 'baz']);

        $result = SampleMediable::withMedia(['bar', 'foo'], true, true)->first();
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2, 2], $result->media->pluck('id')->toArray());
        $this->assertTrue($result->media[0]->relationLoaded('originalMedia'));
        $this->assertTrue($result->media[0]->relationLoaded('variants'));

        $result = SampleMediable::withMediaAndVariants(['bar', 'foo'], true)->first();
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2, 2], $result->media->pluck('id')->toArray());
        $this->assertTrue($result->media[0]->relationLoaded('originalMedia'));
        $this->assertTrue($result->media[0]->relationLoaded('variants'));

        $result = SampleMediable::withMediaMatchAll(['bar', 'foo'], true)->first();
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2, 2], $result->media->pluck('id')->toArray());
        $this->assertTrue($result->media[0]->relationLoaded('originalMedia'));
        $this->assertTrue($result->media[0]->relationLoaded('variants'));

        $result = SampleMediable::withMediaAndVariantsMatchAll(['bar', 'foo'])->first();
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2, 2], $result->media->pluck('id')->toArray());
        $this->assertTrue($result->media[0]->relationLoaded('originalMedia'));
        $this->assertTrue($result->media[0]->relationLoaded('variants'));
    }

    public function test_it_can_lazy_eager_load_media()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media = factory(Media::class)->create();
        $mediable->attachMedia($media, 'foo');

        $result = SampleMediable::first();
        $this->assertSame($result, $result->loadMedia());
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertFalse($result->media[0]->relationLoaded('originalMedia'));
        $this->assertFalse($result->media[0]->relationLoaded('variants'));
    }

    public function test_it_can_lazy_eager_load_media_by_tag()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);
        $mediable->attachMedia($media1, 'foo');
        $mediable->attachMedia($media2, 'bar');

        $result = SampleMediable::first();
        $this->assertSame($result, $result->loadMedia(['bar']));
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2], $result->media->pluck('id')->toArray());
        $this->assertFalse($result->media[0]->relationLoaded('originalMedia'));
        $this->assertFalse($result->media[0]->relationLoaded('variants'));
    }

    public function test_it_can_lazy_eager_load_media_by_tag_matching_all()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);
        $mediable->attachMedia($media1, 'foo');
        $mediable->attachMedia($media2, ['bar', 'foo', 'baz']);

        $result = SampleMediable::first();
        $this->assertSame($result, $result->loadMedia(['bar', 'foo'], true));
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2, 2], $result->media->pluck('id')->toArray());
        $this->assertFalse($result->media[0]->relationLoaded('originalMedia'));
        $this->assertFalse($result->media[0]->relationLoaded('variants'));

        $result = SampleMediable::first();
        $this->assertSame($result, $result->loadMediaMatchAll(['bar', 'foo']));
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2, 2], $result->media->pluck('id')->toArray());
        $this->assertFalse($result->media[0]->relationLoaded('originalMedia'));
        $this->assertFalse($result->media[0]->relationLoaded('variants'));
    }

    public function test_it_can_lazy_eager_load_media_with_variants()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media = factory(Media::class)->create();
        $mediable->attachMedia($media, 'foo');

        $result = SampleMediable::first();
        $this->assertSame($result, $result->loadMedia([], false, true));
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertTrue($result->media[0]->relationLoaded('originalMedia'));
        $this->assertTrue($result->media[0]->relationLoaded('variants'));

        $result = SampleMediable::first();
        $this->assertSame($result, $result->loadMediaWithVariants());
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertTrue($result->media[0]->relationLoaded('originalMedia'));
        $this->assertTrue($result->media[0]->relationLoaded('variants'));
    }

    public function test_it_can_lazy_eager_load_media_with_variants_by_tag()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);
        $mediable->attachMedia($media1, 'foo');
        $mediable->attachMedia($media2, 'bar');

        $result = SampleMediable::first();
        $this->assertSame($result, $result->loadMedia(['bar'], false, true));
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2], $result->media->pluck('id')->toArray());
        $this->assertTrue($result->media[0]->relationLoaded('originalMedia'));
        $this->assertTrue($result->media[0]->relationLoaded('variants'));

        $result = SampleMediable::first();
        $this->assertSame($result, $result->loadMediaWithVariants(['bar']));
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2], $result->media->pluck('id')->toArray());
        $this->assertTrue($result->media[0]->relationLoaded('originalMedia'));
        $this->assertTrue($result->media[0]->relationLoaded('variants'));
    }

    public function test_it_can_lazy_eager_load_media_with_variants_by_tag_matching_all()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);
        $mediable->attachMedia($media1, 'foo');
        $mediable->attachMedia($media2, ['bar', 'foo', 'baz']);

        $result = SampleMediable::first();
        $this->assertSame($result, $result->loadMedia(['bar', 'foo'], true, true));
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2, 2], $result->media->pluck('id')->toArray());
        $this->assertTrue($result->media[0]->relationLoaded('originalMedia'));
        $this->assertTrue($result->media[0]->relationLoaded('variants'));

        $result = SampleMediable::first();
        $this->assertSame($result, $result->loadMediaWithVariants(['bar', 'foo'], true));
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2, 2], $result->media->pluck('id')->toArray());
        $this->assertTrue($result->media[0]->relationLoaded('originalMedia'));
        $this->assertTrue($result->media[0]->relationLoaded('variants'));

        $result = SampleMediable::first();
        $this->assertSame($result, $result->loadMediaMatchAll(['bar', 'foo'], true));
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2, 2], $result->media->pluck('id')->toArray());
        $this->assertTrue($result->media[0]->relationLoaded('originalMedia'));
        $this->assertTrue($result->media[0]->relationLoaded('variants'));

        $result = SampleMediable::first();
        $this->assertSame($result, $result->loadMediaWithVariantsMatchAll(['bar', 'foo']));
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertEquals([2, 2], $result->media->pluck('id')->toArray());
        $this->assertTrue($result->media[0]->relationLoaded('originalMedia'));
        $this->assertTrue($result->media[0]->relationLoaded('variants'));
    }

    public function test_it_uses_custom_collection()
    {
        $mediable = factory(SampleMediable::class)->make();
        $this->assertInstanceOf(MediableCollection::class, $mediable->newCollection([]));
    }

    public function test_it_cascades_relationship_on_delete()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media = factory(Media::class)->create();
        $mediable->attachMedia($media, 'foo');

        $mediable->delete();
        $this->assertEquals(0, $mediable->getMedia('foo')->count());
    }

    public function test_it_doesnt_cascade_relationship_on_soft_delete()
    {
        $mediable = factory(SampleMediableSoftDelete::class)->create();
        $media = factory(Media::class)->create();
        $mediable->attachMedia($media, 'foo');

        $mediable->delete();
        $this->assertEquals(1, $mediable->getMedia('foo')->count());
    }

    public function test_it_cascades_relationships_on_soft_delete_with_config()
    {
        $mediable = factory(SampleMediableSoftDelete::class)->create();
        $media = factory(Media::class)->create();
        $mediable->attachMedia($media, 'foo');

        config()->set('mediable.detach_on_soft_delete', true);

        $mediable->delete();
        $this->assertEquals(0, $mediable->getMedia('foo')->count());
    }

    public function test_it_cascades_relationship_on_force_delete()
    {
        $mediable = factory(SampleMediableSoftDelete::class)->create();
        $media = factory(Media::class)->create();
        $mediable->attachMedia($media, 'foo');

        $mediable->forceDelete();
        $this->assertEquals(0, $mediable->getMedia('foo')->count());
    }

    public function test_it_reads_highest_order()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media = factory(Media::class)->create(['id' => 1]);
        $method = $this->getPrivateMethod($mediable, 'getOrderValueForTags');

        $this->assertEquals(['foo' => 0], $method->invoke($mediable, 'foo'));
        $mediable->attachMedia($media, 'foo');
        $this->assertEquals(['foo' => 1], $method->invoke($mediable, 'foo'));
    }

    public function test_it_increments_order()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);
        $media3 = factory(Media::class)->create(['id' => 3]);

        $mediable->attachMedia($media2, 'foo');
        $mediable->attachMedia($media3, 'foo');
        $mediable->attachMedia($media1, 'bar');
        $mediable->attachMedia($media1, 'foo');

        $this->assertEquals(
            [2 => 1, 3 => 2, 1 => 3],
            $mediable->getMedia('foo')->pluck('pivot.order', 'id')->toArray()
        );
        $this->assertEquals(
            [1 => 1],
            $mediable->getMedia('bar')->pluck(
                'pivot.order',
                'id'
            )->toArray()
        );
    }

    public function test_it_increments_order_when_attaching_multiple()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);
        $media3 = factory(Media::class)->create(['id' => 3]);

        $mediable->attachMedia([2, 3, 1], 'foo');

        $this->assertEquals(
            [2 => 1, 3 => 2, 1 => 3],
            $mediable->getMedia('foo')->pluck('pivot.order', 'id')->toArray()
        );
    }

    public function test_it_can_unset_order()
    {
        $mediable = factory(SampleMediable::class)->make();

        $query = $mediable->media()->unordered()->toSql();

        $this->assertEquals(0, preg_match('/order by `order`/i', $query));
    }

    public function test_it_can_create_mediables_on_custom_table()
    {
        config()->set('mediable.mediables_table', 'prefixed_mediables');

        $media = factory(Media::class)->create();
        $mediable = factory(SampleMediable::class)->create();
        $mediable->attachMedia($media, 'foo');

        $this->assertEmpty(DB::table('mediables')->get());
        $this->assertCount(1, DB::table('prefixed_mediables')->get());
    }
}
