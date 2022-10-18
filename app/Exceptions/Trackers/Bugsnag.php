<?php

namespace App\Exceptions\Trackers;

use Throwable;

class Bugsnag
{
    public static function beforeSend(Throwable $e): void
    {
        app('bugsnag')->setAppVersion(version('short'));

        app('bugsnag')->registerCallback(function ($report) {
            $report->setMetaData([
                'akaunting' => [
                    'company_id' => (string) company_id(),
                    'locale' => (string) app()->getLocale(),
                    'timezone' => (string) config('app.timezone'),
                ]
            ]);
        });
    }
}
