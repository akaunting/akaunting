<?php

namespace App\Utilities;

use Illuminate\Support\Collection;
use Illuminate\Support\Str as IStr;

class Str
{
    public static function getInitials($value, $length = 2)
    {
        $words = new Collection(explode(' ', $value));

        // if name contains single word, use first N character
        if ($words->count() === 1) {
            $initial = static::getInitialFromOneWord($value, $words, $length);
        } else {
            $initial = static::getInitialFromMultipleWords($words, $length);
        }

        $initial = strtoupper($initial);

        if (language()->direction() == 'rtl') {
            $initial = collect(mb_str_split($initial))->reverse()->implode('');
        }

        return $initial;
    }

    public static function getInitialFromOneWord($value, $words, $length)
    {
        $initial = (string) $words->first();

        if (strlen($value) >= $length) {
            $initial = IStr::substr($value, 0, $length);
        }

        return $initial;
    }

    public static function getInitialFromMultipleWords($words, $length)
    {
        // otherwise, use initial char from each word
        $initials = new Collection();

        $words->each(function ($word) use ($initials) {
            $initials->push(IStr::substr($word, 0, 1));
        });

        return static::selectInitialFromMultipleInitials($initials, $length);
    }

    public static function selectInitialFromMultipleInitials($initials, $length)
    {
        return $initials->slice(0, $length)->implode('');
    }
}
