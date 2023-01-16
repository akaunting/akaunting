<?php

namespace App\Exceptions\Trackers;

use App\Traits\Trackers as Base;
use Illuminate\Support\Str;
use Sentry\Event;
use Sentry\EventHint;
use Sentry\Tracing\SamplingContext;

class Sentry
{
    use Base;

    public static function beforeSend(Event $event, ?EventHint $hint): ?Event
    {
        $event->setRelease(version('short'));

        $tags = static::getTrackerTags();

        $event->setTags($tags);

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
}
