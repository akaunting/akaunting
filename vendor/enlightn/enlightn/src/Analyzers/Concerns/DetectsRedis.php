<?php

namespace Enlightn\Enlightn\Analyzers\Concerns;

trait DetectsRedis
{
    /**
     * Determine if the application uses any Redis services.
     *
     * @return bool
     */
    protected function appUsesRedis()
    {
        // We assume here that if the cache, session, broadcasting or queue is powered by Redis,
        // then the application is using Redis.
        return (config('cache.stores.'.config('cache.default').'.driver') === 'redis'
            || config('broadcasting.connections.'.config('broadcasting.default').'.driver') === 'redis'
            || config('session.driver') === 'redis'
            || config('queue.connections.'.config('queue.default').'.driver') === 'redis');
    }
}
