<?php

namespace Monooso\Unobserve;

trait CanMute
{
    public static function mute($events = null)
    {
        $instance = new static();
        resolve(ProxyManager::class)->register($instance, static::normalizeEvents($events));
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

    public static function unmute()
    {
        resolve(ProxyManager::class)->unregister(new static);
    }
}
