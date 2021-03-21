<?php

namespace Laratrust\Traits;

use Illuminate\Support\Str;

trait LaratrustHasEvents
{
    protected static $laratrustObservables = [
        'roleAttached',
        'roleDetached',
        'permissionAttached',
        'permissionDetached',
        'roleSynced',
        'permissionSynced',
    ];

    /**
     * Register an observer to the Laratrust events.
     *
     * @param  object|string  $class
     * @return void
     */
    public static function laratrustObserve($class)
    {
        $className = is_string($class) ? $class : get_class($class);

        foreach (self::$laratrustObservables as $event) {
            if (method_exists($class, $event)) {
                static::registerLaratrustEvent(Str::snake($event, '.'), $className.'@'.$event);
            }
        }
    }

    public static function laratrustFlushObservables()
    {
        foreach (self::$laratrustObservables as $event) {
            $event = Str::snake($event, '.');
            static::$dispatcher->forget("laratrust.{$event}: " . static::class);
        }
    }

    /**
     * Fire the given event for the model.
     *
     * @param  string  $event
     * @param  array  $payload
     * @return mixed
     */
    protected function fireLaratrustEvent($event, array $payload)
    {
        if (! isset(static::$dispatcher)) {
            return true;
        }

        return static::$dispatcher->dispatch(
            "laratrust.{$event}: ".static::class,
            $payload
        );
    }

    /**
     * Register a laratrust event with the dispatcher.
     *
     * @param  string  $event
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function registerLaratrustEvent($event, $callback)
    {
        if (isset(static::$dispatcher)) {
            $name = static::class;

            static::$dispatcher->listen("laratrust.{$event}: {$name}", $callback);
        }
    }

    /**
     * Register a role attached laratrust event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function roleAttached($callback)
    {
        static::registerLaratrustEvent('role.attached', $callback);
    }

    /**
     * Register a role detached laratrust event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function roleDetached($callback)
    {
        static::registerLaratrustEvent('role.detached', $callback);
    }

    /**
     * Register a permission attached laratrust event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function permissionAttached($callback)
    {
        static::registerLaratrustEvent('permission.attached', $callback);
    }

    /**
     * Register a permission detached laratrust event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function permissionDetached($callback)
    {
        static::registerLaratrustEvent('permission.detached', $callback);
    }

    /**
     * Register a role synced laratrust event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function roleSynced($callback)
    {
        static::registerLaratrustEvent('role.synced', $callback);
    }

    /**
     * Register a permission synced laratrust event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function permissionSynced($callback)
    {
        static::registerLaratrustEvent('permission.synced', $callback);
    }
}
