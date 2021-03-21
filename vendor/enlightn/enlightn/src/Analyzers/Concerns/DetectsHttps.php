<?php

namespace Enlightn\Enlightn\Analyzers\Concerns;

use Illuminate\Support\Str;

trait DetectsHttps
{
    /**
     * Determine if the application is an HTTPS only app.
     *
     * @return bool
     */
    protected function appIsHttpsOnly()
    {
        // We assume here that if the app URL points to a URL with the https protocol or if the secure attribute
        // is set to true (as the default for all app cookies), then the app is an HTTPS only app.
        return Str::contains(config('app.url'), 'https://') || config('session.secure') == true;
    }
}
