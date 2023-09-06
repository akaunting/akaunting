<?php

namespace Sentry\Laravel\Util;

/**
 * @internal
 */
class Filesize
{
    /**
     * Convert bytes to human readable format.
     *
     * Credit: https://stackoverflow.com/a/23888858/1580028
     *
     * @param int $bytes    The amount of bytes to convert to human readable format.
     * @param int $decimals The number of decimals to use in the resulting string.
     *
     * @return string
     */
    public static function toHuman(int $bytes, int $decimals = 2): string
    {
        $size   = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = (int)floor((strlen($bytes) - 1) / 3);

        if ($factor === 0) {
            $decimals = 0;
        }

        return sprintf("%.{$decimals}f %s", $bytes / (1024 ** $factor), $size[$factor]);
    }
}
