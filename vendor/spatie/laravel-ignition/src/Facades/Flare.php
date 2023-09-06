<?php

namespace Spatie\LaravelIgnition\Facades;

use Illuminate\Support\Facades\Facade;
use Spatie\LaravelIgnition\Support\SentReports;

/**
 * @method static void glow(string $name, string $messageLevel = \Spatie\FlareClient\Enums\MessageLevels::INFO, array $metaData = [])
 * @method static void context($key, $value)
 * @method static void group(string $groupName, array $properties)
 *
 * @see \Spatie\FlareClient\Flare
 */
class Flare extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Spatie\FlareClient\Flare::class;
    }

    public static function sentReports(): SentReports
    {
        return app(SentReports::class);
    }
}
