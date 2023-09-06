<?php

declare(strict_types=1);

namespace ZipStream;

use RuntimeException;

/**
 * @internal
 * TODO: Make class readonly when requiring PHP 8.2 exclusively
 */
class PackField
{
    public const MAX_V = 0xFFFFFFFF;

    public const MAX_v = 0xFFFF;

    public function __construct(
        public readonly string $format,
        public readonly int|string $value
    ) {
    }

    /**
     * Create a format string and argument list for pack(), then call
     * pack() and return the result.
     */
    public static function pack(self ...$fields): string
    {
        $fmt = array_reduce($fields, function (string $acc, self $field) {
            return $acc . $field->format;
        }, '');

        $args = array_map(function (self $field) {
            switch($field->format) {
                case 'V':
                    if ($field->value > self::MAX_V) {
                        throw new RuntimeException(print_r($field->value, true) . ' is larger than 32 bits');
                    }
                    break;
                case 'v':
                    if ($field->value > self::MAX_v) {
                        throw new RuntimeException(print_r($field->value, true) . ' is larger than 16 bits');
                    }
                    break;
                case 'P': break;
                default:
                    break;
            }

            return $field->value;
        }, $fields);

        return pack($fmt, ...$args);
    }
}
