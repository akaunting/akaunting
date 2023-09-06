<?php

declare(strict_types=1);

namespace ZipStream;

/**
 * @internal
 */
abstract class DataDescriptor
{
    private const SIGNATURE = 0x08074b50;

    public static function generate(
        int $crc32UncompressedData,
        int $compressedSize,
        int $uncompressedSize,
    ): string {
        return PackField::pack(
            new PackField(format: 'V', value: self::SIGNATURE),
            new PackField(format: 'V', value: $crc32UncompressedData),
            new PackField(format: 'V', value: $compressedSize),
            new PackField(format: 'V', value: $uncompressedSize),
        );
    }
}
