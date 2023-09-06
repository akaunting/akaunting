<?php

declare(strict_types=1);

namespace ZipStream;

use DateTimeInterface;

/**
 * @internal
 */
abstract class CentralDirectoryFileHeader
{
    private const SIGNATURE = 0x02014b50;

    public static function generate(
        int $versionMadeBy,
        int $versionNeededToExtract,
        int $generalPurposeBitFlag,
        CompressionMethod $compressionMethod,
        DateTimeInterface $lastModificationDateTime,
        int $crc32,
        int $compressedSize,
        int $uncompressedSize,
        string $fileName,
        string $extraField,
        string $fileComment,
        int $diskNumberStart,
        int $internalFileAttributes,
        int $externalFileAttributes,
        int $relativeOffsetOfLocalHeader,
    ): string {
        return PackField::pack(
            new PackField(format: 'V', value: self::SIGNATURE),
            new PackField(format: 'v', value: $versionMadeBy),
            new PackField(format: 'v', value: $versionNeededToExtract),
            new PackField(format: 'v', value: $generalPurposeBitFlag),
            new PackField(format: 'v', value: $compressionMethod->value),
            new PackField(format: 'V', value: Time::dateTimeToDosTime($lastModificationDateTime)),
            new PackField(format: 'V', value: $crc32),
            new PackField(format: 'V', value: $compressedSize),
            new PackField(format: 'V', value: $uncompressedSize),
            new PackField(format: 'v', value: strlen($fileName)),
            new PackField(format: 'v', value: strlen($extraField)),
            new PackField(format: 'v', value: strlen($fileComment)),
            new PackField(format: 'v', value: $diskNumberStart),
            new PackField(format: 'v', value: $internalFileAttributes),
            new PackField(format: 'V', value: $externalFileAttributes),
            new PackField(format: 'V', value: $relativeOffsetOfLocalHeader),
        ) . $fileName . $extraField . $fileComment;
    }
}
