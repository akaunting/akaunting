<?php

namespace Akaunting\Version;

use Illuminate\Support\Str;

class Version
{
    /**
     * The Laravel application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Normalized Laravel Version.
     *
     * @var string
     */
    protected $version;

    /**
     * True when this is a Lumen application.
     *
     * @var bool
     */
    protected $is_lumen = false;

    /**
     * @param Application $app
     */
    public function __construct($app = null)
    {
        if (!$app) {
            $app = app();   //Fallback when $app is not given
        }

        $this->app = $app;
        $this->version = $app->version();
        $this->is_lumen = Str::contains($this->version, 'Lumen');
    }

    /**
     * Get name.
     *
     * @return string
     **/
    public static function name()
    {
        return config('version.name');
    }

    /**
     * Get code.
     *
     * @return string
     **/
    public static function code()
    {
        return config('version.code');
    }

    /**
     * Get major version.
     *
     * @return string
     **/
    public static function major()
    {
        return config('version.major');
    }

    /**
     * Get minor version.
     *
     * @return string
     **/
    public static function minor()
    {
        return config('version.minor');
    }

    /**
     * Get patch version.
     *
     * @return string
     **/
    public static function patch()
    {
        return config('version.patch');
    }

    /**
     * Get build version.
     *
     * @return string
     **/
    public static function build()
    {
        return config('version.build');
    }

    /**
     * Get status.
     *
     * @return string
     **/
    public static function status()
    {
        return config('version.status');
    }

    /**
     * Get date.
     *
     * @return string
     **/
    public static function date()
    {
        return config('version.date');
    }

    /**
     * Get time.
     *
     * @return string
     **/
    public static function time()
    {
        return config('version.time');
    }

    /**
     * Get zone.
     *
     * @return string
     **/
    public static function zone()
    {
        return config('version.zone');
    }

    /**
     * Get release version.
     *
     * @return string
     **/
    public static function release()
    {
        return static::major() . '.' . static::minor();
    }

    /**
     * Get short version.
     *
     * @return string
     **/
    public static function short()
    {
        return static::release() . '.' . static::patch();
    }

    /**
     * Get long version.
     *
     * @return string
     **/
    public static function long()
    {
        return static::name() . ' ' . static::short() . ' '
        . static::status() . ' [ ' . static::code() . ' ] ' . static::date() . ' '
        . static::time() . ' ' . static::zone();
    }
}
