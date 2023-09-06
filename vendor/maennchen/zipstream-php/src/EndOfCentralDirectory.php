<?php

declare(strict_types=1);

namespace ZipStream;

/**
 * @internal
 */
abstract class EndOfCentralDirectory
{
    private const SIGNATURE = 0x06054b50;

    public static function generate(
        int $numberOfThisDisk,
        int $numberOfTheDiskWithCentralDirectoryStart,
        int $numberOfCentralDirectoryEntriesOnThisDisk,
        int $numberOfCentralDirectoryEntries,
        int $sizeOfCentralDirectory,
        int $centralDirectoryStartOffsetOnDisk,
        string $zipFileComment,
    ): string {
        /** @psalm-suppress MixedArgument */
        return PackField::pack(
            new PackField(format: 'V', value: static::SIGNATURE),
            new PackField(format: 'v', value: $numberOfThisDisk),
            new PackField(format: 'v', value: $numberOfTheDiskWithCentralDirectoryStart),
            new PackField(format: 'v', value: $numberOfCentralDirectoryEntriesOnThisDisk),
            new PackField(format: 'v', value: $numberOfCentralDirectoryEntries),
            new PackField(format: 'V', value: $sizeOfCentralDirectory),
            new PackField(format: 'V', value: $centralDirectoryStartOffsetOnDisk),
            new PackField(format: 'v', value: strlen($zipFileComment)),
        ) . $zipFileComment;
    }
}
