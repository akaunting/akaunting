<?php

namespace Akaunting\MutableObserver\Traits;

use Akaunting\MutableObserver\ProxyManager;

trait Mutable
{
    public static function mute($events = null): void
    {
        $instance = new static();

        app(ProxyManager::class)->register($instance, static::normalizeEvents($events));
    }

    public static function unmute(): void
    {
        $instance = new static();

        app(ProxyManager::class)->unregister($instance);
    }

    protected static function normalizeEvents($events): array
    {
        if (is_null($events)) {
            $events = ['*'];
        }

        if (! is_array($events)) {
            $events = [$events];
        }

        return $events;
    }
}
