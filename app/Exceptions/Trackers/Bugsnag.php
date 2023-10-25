<?php

namespace App\Exceptions\Trackers;

use App\Traits\Trackers as Base;
use Akaunting\Version\Version;
use Throwable;

class Bugsnag
{
    use Base;

    public static function beforeSend(Throwable $e): void
    {
        app('bugsnag')->setAppVersion(Version::short());

        $tags = static::getTrackerTags();

        app('bugsnag')->registerCallback(function ($report) use($tags) {
            $report->setMetaData([
                'akaunting' => $tags
            ]);
        });
    }
}
