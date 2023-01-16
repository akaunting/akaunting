<?php

namespace App\Listeners\Module;

use Illuminate\Support\Facades\Cache;

class ClearCache
{
    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle($event)
    {
        if (config('module.cache.enabled')) {
            Cache::forget(config('module.cache.key'));
        }

        Cache::forget('apps.notifications');
        Cache::forget('apps.suggestions');
        Cache::forget('apps.installed.' . $event->company_id);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $dispatcher
     */
    public function subscribe($dispatcher)
    {
        $events = [
            'App\Events\Install\UpdateCacheCleared',
            'App\Events\Module\Copied',
            'App\Events\Module\Enabled',
            'App\Events\Module\Disabled',
            'App\Events\Module\Uninstalled',
        ];

        foreach ($events as $event) {
            $dispatcher->listen($event, 'App\Listeners\Module\ClearCache@handle');
        }
    }
}
