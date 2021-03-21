<?php

namespace Akaunting\Language;

use Illuminate\Support\Str;

class Language
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
     * Get single flags view.
     *
     * @param string $code
     *
     * @return mixed
     **/
    public static function flag($code = 'default')
    {
        if ($code == 'default') {
            $code = app()->getLocale();
        }

        $name = self::getName($code);
        $code = self::country($code);

        return view('vendor.language.flag', compact('code', 'name'));
    }

    /**
     * Get country code based on locale.
     *
     * @param string $locale
     *
     * @return mixed
     **/
    public static function country($locale = 'default')
    {
        if ($locale == 'default') {
            $locale = app()->getLocale();
        }

        if (config('language.mode.code', 'short') == 'short') {
            $code = strtolower(substr(self::getLongCode($locale), 3));
        } else {
            $code = strtolower(substr($locale, 3));
        }

        return $code;
    }

    /**
     * Get all flags view.
     *
     * @return mixed
     **/
    public static function flags()
    {
        return view('vendor.language.flags');
    }

    /**
     * Return true if $code is an allowed lang.
     * Get all allowed languages.
     *
     * @param string $locale
     *
     * @return bool|array
     **/
    public static function allowed($locale = null)
    {
        if ($locale) {
            return in_array($locale, array_keys(self::allowed()));
        }

        if (config('language.allowed')) {
            return self::names(array_merge(config('language.allowed'), [config('app.locale')]));
        } else {
            return self::names([config('app.locale')]);
        }
    }

    /**
     * Add names to an array of language codes as [$code => $language].
     *
     * @param array $codes
     *
     * @return array
     **/
    public static function names($codes)
    {
        // Get mode
        $mode = config('language.mode');

        // Get languages from config
        $languages = config('language.all');

        $array = [];

        // Generate an array with $code as key and $code language as value
        foreach ($codes as $code) {
            $lang_name = 'Unknown';

            foreach ($languages as $language) {
                if ($language[$mode['code']] == $code) {
                    $lang_name = $language[$mode['name']];
                }
            }

            $array[$code] = $lang_name;
        }

        return $array;
    }

    /**
     * Add names to an array of language codes as [$language => $code].
     *
     * @param array $langs
     *
     * @return array
     **/
    public static function codes($langs)
    {
        // Get mode
        $mode = config('language.mode');

        // Get languages from config
        $languages = config('language.all');

        $array = [];

        // Generate an array with $lang as key and $lang code as value
        foreach ($langs as $lang) {
            $lang_code = 'unk';

            foreach ($languages as $language) {
                if ($language[$mode['name']] == $lang) {
                    $lang_code = $language[$mode['code']];
                }
            }

            $array[$lang] = $lang_code;
        }

        return $array;
    }

    /**
     * Returns the url to set up language and return back.
     *
     * @param string $code
     *
     * @return string
     **/
    public static function back($code)
    {
        return route('language::back', ['locale' => $code]);
    }

    /**
     * Returns the url to set up language and return to url('/').
     *
     * @param string $code
     *
     * @return string
     **/
    public static function home($code)
    {
        return route('language::home', ['locale' => $code]);
    }

    /**
     * Returns the language code.
     *
     * @param string $name
     *
     * @return string
     **/
    public static function getCode($name = 'default')
    {
        if ($name == 'default') {
            $name = self::getName();
        }

        return self::codes([$name])[$name];
    }

    /**
     * Returns the language long code.
     *
     * @param string $short
     *
     * @return string
     **/
    public static function getLongCode($short = 'default')
    {
        if ($short == 'default') {
            $short = app()->getLocale();
        }

        $long = 'en-GB';

        // Get languages from config
        $languages = config('language.all');

        foreach ($languages as $language) {
            if ($language['short'] != $short) {
                continue;
            }

            $long = $language['long'];
        }

        return $long;
    }

    /**
     * Returns the language short code.
     *
     * @param string $long
     *
     * @return string
     **/
    public static function getShortCode($long = 'default')
    {
        if ($long == 'default') {
            $long = app()->getLocale();
        }

        $short = 'en';

        // Get languages from config
        $languages = config('language.all');

        foreach ($languages as $language) {
            if ($language['long'] != $long) {
                continue;
            }

            $short = $language['short'];
        }

        return $short;
    }

    /**
     * Returns the language name.
     *
     * @param string $code
     *
     * @return string
     **/
    public static function getName($code = 'default')
    {
        if ($code == 'default') {
            $code = app()->getLocale();
        }

        return self::names([$code])[$code];
    }
}
