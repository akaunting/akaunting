<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Trackers
{
    public static function getTrackerTags(): array
    {
        return [
            'company_id' => (string) company_id(),
            'locale' => (string) app()->getLocale(),
            'timezone' => (string) config('app.timezone'),
            'app_type' => (string) static::getAppType(),
            'route_name' => (string) static::getRouteName(),
        ];
    }

    public static function getAppType(): string
    {
        $hostname = gethostname();

        if (Str::contains($hostname, '-queue-')) {
            $app_type = 'queue';
        } elseif (Str::contains($hostname, '-cron-')) {
            $app_type = 'cron';
        } elseif (request_is_api()) {
            $app_type = 'api';
        } elseif (app()->runningInConsole()) {
            $app_type = 'console';
        } else {
            $app_type = 'ui';
        }

        return $app_type;
    }

    public static function getRouteName(): ?string
    {
        return request()->route()?->getName();
    }
}
