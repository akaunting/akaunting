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
        if (is_string($time)) {
            $time = static::translateTimeString($time, static::getLocale(), 'en');
        }

        return parent::rawParse($time, $timezone);
    }

    public static function createFromFormatWithCurrentLocale($format, $time = null, $timezone = null)
    {
        if (is_string($time)) {
            $time = static::translateTimeString($time, static::getLocale(), 'en');
        }

        return parent::rawCreateFromFormat($format, $time, $timezone);
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
}
