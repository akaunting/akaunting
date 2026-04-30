<?php

namespace App\Utilities;

use Carbon\Carbon;

class Date extends Carbon
{
    /**
     * Function to call instead of format.
     *
     * @var string|callable|null
     */
    protected static $formatFunction = 'translatedFormat';

    /**
     * Function to call instead of createFromFormat.
     *
     * @var string|callable|null
     */
    protected static $createFromFormatFunction = 'createFromFormatWithCurrentLocale';

    /**
     * Function to call instead of parse.
     *
     * @var string|callable|null
     */
    protected static $parseFunction = 'parseWithCurrentLocale';

    /**
     * Indicates if months should be calculated with overflow.
     * Global setting.
     *
     * @var bool
     */
    protected static $monthsOverflow = false;

    /**
     * Indicates if years should be calculated with overflow.
     * Global setting.
     *
     * @var bool
     */
    protected static $yearsOverflow = false;

    public static function parseWithCurrentLocale($time = null, $timezone = null)
    {
        return static::parseWithFallbackLocales($time, $timezone);
    }

    public static function createFromFormatWithCurrentLocale($format, $time = null, $timezone = null)
    {
        if (! is_string($time)) {
            return parent::rawCreateFromFormat($format, $time, $timezone);
        }

        foreach (static::getFallbackLocales() as $locale) {
            try {
                return parent::createFromLocaleFormat($format, $locale, $time, $timezone);
            } catch (\Throwable $e) {
            }
        }

        return parent::rawCreateFromFormat($format, $time, $timezone);
    }

    public static function parseWithFallbackLocales($time = null, $timezone = null, array $locales = [])
    {
        if (! is_string($time)) {
            return parent::rawParse($time, $timezone);
        }

        foreach (static::getFallbackLocales($locales) as $locale) {
            try {
                return parent::parseFromLocale($time, $locale, $timezone);
            } catch (\Throwable $e) {
            }
        }

        return parent::rawParse($time, $timezone);
    }

    /**
     * Get the language portion of the locale.
     *
     * @param string $locale
     * @return string
     */
    public static function getLanguageFromLocale($locale)
    {
        $parts = explode('_', str_replace('-', '_', $locale));

        return $parts[0];
    }

    protected static function getFallbackLocales(array $locales = []): array
    {
        $appLocale = null;
        $fallbackLocale = null;

        if (function_exists('app')) {
            try {
                if (app()->bound('translator')) {
                    $appLocale = app()->getLocale();
                }

                if (app()->bound('config')) {
                    $fallbackLocale = app('config')->get('app.fallback_locale');
                }
            } catch (\Throwable $e) {
            }
        }

        $candidates = array_merge(
            $locales,
            [static::getLocale()],
            [$appLocale],
            [$fallbackLocale],
            ['en']
        );

        $normalized = [];

        foreach ($candidates as $locale) {
            if (empty($locale)) {
                continue;
            }

            $normalized[] = $locale;

            $language = static::getLanguageFromLocale($locale);

            if ($language !== $locale) {
                $normalized[] = $language;
            }
        }

        return array_values(array_unique($normalized));
    }
}
