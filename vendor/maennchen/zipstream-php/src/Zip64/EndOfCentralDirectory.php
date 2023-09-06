<?php

declare(strict_types=1);

namespace ZipStream\Zip64;

use ZipStream\PackField;

/**
 * @internal
 */
abstract class EndOfCentralDirectory
{
    private const SIGNATURE = 0x06064b50;

    public static function generate(
        int $versionMadeBy,
        int $versionNeededToExtract,
        int $numberOfThisDisk,
        int $numberOfTheDiskWithCentralDirectoryStart,
        int $numberOfCentralDirectoryEntriesOnThisDisk,
        int $numberOfCentralDirectoryEntries,
        int $sizeOfCentralDirectory,
        int $centralDirectoryStartOffsetOnDisk,
        string $extensibleDataSector,
    ): string {
        $recordSize = 44 + strlen($extensibleDataSector); // (length of block - 12) = 44;

        /** @psalm-suppress MixedArgument */
        return PackField::pack(
            new PackField(format: 'V', value: static::SIGNATURE),
            new PackField(format: 'P', value: $recordSize),
            new PackField(format: 'v', value: $versionMadeBy),
            new PackField(format: 'v', value: $versionNeededToExtract),
            new PackField(format: 'V', value: $numberOfThisDisk),
            new PackField(format: 'V', value: $numberOfTheDiskWithCentralDirectoryStart),
            new PackField(format: 'P', value: $numberOfCentralDirectoryEntriesOnThisDisk),
            new PackField(format: 'P', value: $numberOfCentralDirectoryEntries),
            new PackField(format: 'P', value: $sizeOfCentralDirectory),
            new PackField(format: 'P', value: $centralDirectoryStartOffsetOnDisk),
        ) . $extensibleDataSector;
    }
}
