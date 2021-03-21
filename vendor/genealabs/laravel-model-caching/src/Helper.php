<?php

namespace GeneaLabs\LaravelModelCaching;

use Illuminate\Container\Container;

class Helper
{
    public function runDisabled(callable $closure)
    {
        $originalSetting = Container::getInstance()
            ->make("config")
            ->get('laravel-model-caching.enabled');

        Container::getInstance()
            ->make("config")
            ->set(['laravel-model-caching.enabled' => false]);

        $result = $closure();

        Container::getInstance()
            ->make("config")
            ->set(['laravel-model-caching.enabled' => $originalSetting]);

        return $result;
    }
}
