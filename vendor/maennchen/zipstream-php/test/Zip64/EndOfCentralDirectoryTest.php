<?php

declare(strict_types=1);

namespace ZipStream\Test\Zip64;

use PHPUnit\Framework\TestCase;
use ZipStream\Zip64\EndOfCentralDirectory;

class EndOfCentralDirectoryTest extends TestCase
{
    public function testSerializesCorrectly(): void
    {
        $descriptor = EndOfCentralDirectory::generate(
            versionMadeBy: 0x3333,
            versionNeededToExtract: 0x4444,
            numberOfThisDisk: 0x55555555,
            numberOfTheDiskWithCentralDirectoryStart: 0x66666666,
            numberOfCentralDirectoryEntriesOnThisDisk: (0x77777777 << 32) + 0x88888888,
            numberOfCentralDirectoryEntries: (0x99999999 << 32) + 0xAAAAAAAA,
            sizeOfCentralDirectory: (0xBBBBBBBB << 32) + 0xCCCCCCCC,
            centralDirectoryStartOffsetOnDisk: (0xDDDDDDDD << 32) + 0xEEEEEEEE,
            extensibleDataSector: 'foo',
        );

        $this->assertSame(
            bin2hex($descriptor),
            '504b0606' . // 4 bytes;zip64 end of central dir signature - 0x06064b50
            '2f00000000000000' . // 8 bytes; size of zip64 end of central directory record
            '3333' . // 2 bytes; version made by
            '4444' . // 2 bytes; version needed to extract
            '55555555' . // 4 bytes; number of this disk
            '66666666' . // 4 bytes; number of the disk with the start of the central directory
            '8888888877777777' . // 8 bytes; total number of entries in the central directory on this disk
            'aaaaaaaa99999999' . // 8 bytes; total number of entries in the central directory
            'ccccccccbbbbbbbb' . // 8 bytes; size of the central directory
            'eeeeeeeedddddddd' . // 8 bytes; offset of start of central directory with respect to the starting disk number
            bin2hex('foo')
        );
    }
}
