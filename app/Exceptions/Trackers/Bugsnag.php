<?php

namespace App\Exceptions\Trackers;

use App\Traits\Trackers as Base;
use Throwable;

class Bugsnag
{
    use Base;

    public static function beforeSend(Throwable $e): void
    {
        app('bugsnag')->setAppVersion(version('short'));

        $tags = static::getTrackerTags();

        app('bugsnag')->registerCallback(function ($report) use($tags) {
            $report->setMetaData([
                'akaunting' => $tags
            ]);
        });
    }
}
