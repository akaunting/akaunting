<?php

namespace Tests\Feature\Common;

use Illuminate\Queue\Jobs\SyncJob;
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

    public function testItShouldLeaveTheModelCacheAloneForAJobRunInPlace()
    {
        config(['cache.default' => 'file', 'laravel-model-caching.store' => 'array']);

        Cache::store('array')->put('cached-by-the-request', 'value', 60);

        // A job dispatched on the sync connection runs inside the request or command around it
        $this->processJob('sync');

        $this->assertSame('value', Cache::store('array')->get('cached-by-the-request'));
    }

    private function processJob(string $connection = 'redis'): void
    {
        // Other listeners read the job's name, so a real job rather than a mock
        $job = new SyncJob(app(), json_encode([
            'uuid'          => 'queue-model-cache-test',
            'displayName'   => 'QueueModelCacheTest',
            'job'           => 'QueueModelCacheTest',
            'data'          => [],
        ]), $connection, 'default');

        event(new JobProcessing($connection, $job));
    }
}
