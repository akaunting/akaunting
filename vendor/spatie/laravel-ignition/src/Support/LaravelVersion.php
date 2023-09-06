<?php

namespace Spatie\LaravelIgnition\Support;

class LaravelVersion
{
    public static function major(): string
    {
        return explode('.', app()->version())[0];
    }
}
