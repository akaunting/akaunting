<?php

namespace Akaunting\Module\Traits;

trait CanClearModulesCache
{
    /**
     * Clear the modules cache if it is enabled
     */
    public function clearCache()
    {
        if (config('module.cache.enabled') === true) {
            app('cache')->forget(config('module.cache.key'));
        }
    }
}
