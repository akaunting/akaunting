<?php

use Plank\Mediable\Media;
use Plank\Mediable\MediableCollection;

class MediableCollectionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->useDatabase();
    }

    public function test_it_can_lazy_eager_load_media()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media = factory(Media::class)->create();
        $mediable->attachMedia($media, 'foo');

        $result = SampleMediable::first();
        $collection = new MediableCollection([$result]);
        $this->assertSame($collection, $collection->loadMedia());
        $this->assertTrue($collection[0]->relationLoaded('media'));
    }

    public function test_it_can_lazy_eager_load_media_by_tag()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);
        $mediable->attachMedia($media1, 'foo');
        $mediable->attachMedia($media2, 'bar');

        $collection = new MediableCollection([SampleMediable::first()]);
        $return = $collection->loadMedia(['bar']);

        $this->assertSame($collection, $return);
        $this->assertTrue($collection[0]->relationLoaded('media'));
        $this->assertEquals([2], $collection[0]->media->pluck('id')->toArray());
    }

    public function test_it_can_lazy_eager_load_media_by_tag_match_all()
    {
        $mediable = factory(SampleMediable::class)->create();
        $media1 = factory(Media::class)->create(['id' => 1]);
        $media2 = factory(Media::class)->create(['id' => 2]);
        $mediable->attachMedia($media1, 'foo');
        $mediable->attachMedia($media2, ['foo', 'bar', 'baz']);

        $result = SampleMediable::first();
        $collection = new MediableCollection([$result]);
        $this->assertSame($collection, $collection->loadMedia(['foo', 'bar'], true));
        $this->assertTrue($collection[0]->relationLoaded('media'));
        $this->assertEquals([2, 2], $collection[0]->media->pluck('id')->toArray());
    }
}
