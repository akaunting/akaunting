<?php

namespace Tests\Feature\Common;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Cache;
use Tests\Feature\FeatureTestCase;

/**
 * A queue worker runs one job after another in a single process. The model cache keeps its
 * entries in that process ("array" store), so each job left what it cached to the next and
 * the worker's memory only grew. A job starts with the model cache empty.
 */
class QueueModelCacheTest extends FeatureTestCase
{
    public function testAJobShouldStartWithTheModelCacheEmpty()
    {
        // As in production: the application's cache elsewhere, the model cache in memory
        config(['cache.default' => 'file', 'laravel-model-caching.store' => 'array']);

        Cache::store('array')->put('cached-by-the-job-before', 'value', 60);
        Cache::store('file')->put('kept-for-the-next-job', 'value', 60);

        $this->processJob();

        $this->assertNull(Cache::store('array')->get('cached-by-the-job-before'));
        $this->assertSame('value', Cache::store('file')->get('kept-for-the-next-job'));
    }

    public function testItShouldLeaveTheApplicationCacheAloneWhenTheModelCacheSharesIt()
    {
        config(['cache.default' => 'array', 'laravel-model-caching.store' => 'array']);

        Cache::store('array')->put('kept-for-the-next-job', 'value', 60);

        $this->processJob();

        $this->assertSame('value', Cache::store('array')->get('kept-for-the-next-job'));
    }

    private function processJob(): void
    {
        $job = \Mockery::mock(Job::class);
        $job->shouldReceive('payload')->andReturn([]);

        event(new JobProcessing('redis', $job));
    }
}
