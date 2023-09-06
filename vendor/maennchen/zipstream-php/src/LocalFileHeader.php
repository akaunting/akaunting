<?php

declare(strict_types=1);

namespace ZipStream;

use DateTimeInterface;

/**
 * @internal
 */
abstract class LocalFileHeader
{
    private const SIGNATURE = 0x04034b50;

    public static function generate(
        int $versionNeededToExtract,
        int $generalPurposeBitFlag,
        CompressionMethod $compressionMethod,
        DateTimeInterface $lastModificationDateTime,
        int $crc32UncompressedData,
        int $compressedSize,
        int $uncompressedSize,
        string $fileName,
        string $extraField,
    ): string {
        return PackField::pack(
            new PackField(format: 'V', value: self::SIGNATURE),
            new PackField(format: 'v', value: $versionNeededToExtract),
            new PackField(format: 'v', value: $generalPurposeBitFlag),
            new PackField(format: 'v', value: $compressionMethod->value),
            new PackField(format: 'V', value: Time::dateTimeToDosTime($lastModificationDateTime)),
            new PackField(format: 'V', value: $crc32UncompressedData),
            new PackField(format: 'V', value: $compressedSize),
            new PackField(format: 'V', value: $uncompressedSize),
            new PackField(format: 'v', value: strlen($fileName)),
            new PackField(format: 'v', value: strlen($extraField)),
        ) . $fileName . $extraField;
    }
}
