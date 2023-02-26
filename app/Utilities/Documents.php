<?php

namespace App\Utilities;

use Illuminate\Support\Traits\Macroable;

/**
 * Class Documents
 *
 * Contains macroable utility functions for documents.
 *
 * @package App\Utilities
 */
class Documents
{
    use Macroable;

    public static function getNextDocumentNumber(string $type): string
    {
        if (static::hasMacro(__FUNCTION__)) {
            return static::__callStatic(__FUNCTION__, [$type]);
        }

        if ($alias = config('type.document.' . $type . '.alias')) {
            $type = $alias . '.' . str_replace('-', '_', $type);
        }

        $prefix = setting($type . '.number_prefix');
        $next = setting($type . '.number_next');
        $digit = setting($type . '.number_digit');

        return $prefix . str_pad($next, $digit, '0', STR_PAD_LEFT);
    }

    public static function increaseNextDocumentNumber(string $type): void
    {
        if (static::hasMacro(__FUNCTION__)) {
            static::__callStatic(__FUNCTION__, [$type]);
            return;
        }

        if ($alias = config('type.document.' . $type . '.alias')) {
            $type = $alias . '.' . str_replace('-', '_', $type);
        }

        $next = setting($type . '.number_next', 1) + 1;

        setting([$type . '.number_next' => $next]);
        setting()->save();
    }
}
