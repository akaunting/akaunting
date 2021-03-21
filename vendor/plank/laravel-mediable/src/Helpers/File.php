<?php
declare(strict_types=1);

namespace Plank\Mediable\Helpers;

use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;
use Symfony\Component\Mime\MimeTypes;

class File
{
    /**
     * Get the directory name of path, trimming unnecessary `.` and `/` characters.
     * @param  string $path
     * @return string
     */
    public static function cleanDirname(string $path): string
    {
        $dirname = pathinfo($path, PATHINFO_DIRNAME);
        if ($dirname == '.') {
            return '';
        }

        return trim($dirname, '/');
    }

    /**
     * Generate a human readable bytecount string.
     * @param  int $bytes
     * @param  int $precision
     * @return string
     */
    public static function readableSize(int $bytes, int $precision = 1): string
    {
        static $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        if ($bytes === 0) {
            return '0 ' . $units[0];
        }
        $exponent = (int)floor(log($bytes, 1024));
        $value = $bytes / pow(1024, $exponent);

        return round($value, $precision) . ' ' . $units[$exponent];
    }

    /**
     * Returns the extension based on the mime type.
     *
     * If the mime type is unknown, returns null.
     *
     * @param  string $mimeType
     * @return string|null The guessed extension or null if it cannot be guessed
     *
     * @see MimeTypes
     */
    public static function guessExtension(string $mimeType): ?string
    {
        // use Symfony MimeTypes component if available (symfony/http-foundation v4.3+)
        if (class_exists(MimeTypes::class)) {
            return MimeTypes::getDefault()->getExtensions($mimeType)[0] ?? null;
        }

        // fall back to the older ExtensionGuesser class (deprecated since Symfony 4.3)
        return ExtensionGuesser::getInstance()->guess($mimeType);
    }
}
