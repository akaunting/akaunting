<?php

namespace App\Exceptions\Trackers;

use Illuminate\Support\Str;
use Sentry\Event;
use Sentry\EventHint;
use Sentry\Tracing\SamplingContext;

class Sentry
{
    public static function beforeSend(Event $event, ?EventHint $hint): ?Event
    {
        $event->setRelease(version('short'));

        $event->setTags([
            'company_id' => (string) company_id(),
            'locale' => (string) app()->getLocale(),
            'timezone' => (string) config('app.timezone'),
            'app_type' => (string) static::getAppType(),
        ]);

        return $event;
    }

    public static function tracesSampler(SamplingContext $context): float
    {
        if (static::shouldFilterAgent()) {
            return 0.0;
        }

        return config('sentry.traces_sample_rate');
    }

    public static function shouldFilterAgent(): bool
    {
        $user_agent = request()->userAgent();

        $filter_agents = explode(',', env('SENTRY_TRACES_FILTER_AGENTS'));

        foreach ($filter_agents as $filter_agent) {
            if (! Str::contains($user_agent, $filter_agent)) {
                continue;
            }

            return true;
        }

        return false;
    }

    public static function getAppType(): string
    {
        $hostname = gethostname();

        if (Str::contains($hostname, '-queue-')) {
            $app_type = 'queue';
        } elseif (Str::contains($hostname, '-cron-')) {
            $app_type = 'cron';
        } elseif (request()->isApi()) {
            $app_type = 'api';
        } elseif (app()->runningInConsole()) {
            $app_type = 'console';
        } else {
            $app_type = 'ui';
        }

        return $app_type;
    }
}
