<?php

declare(strict_types=1);

namespace ZipStream\Test;

use PHPUnit\Framework\TestCase;
use ZipStream\EndOfCentralDirectory;

class EndOfCentralDirectoryTest extends TestCase
{
    public function testSerializesCorrectly(): void
    {
        $this->assertSame(
            bin2hex(EndOfCentralDirectory::generate(
                numberOfThisDisk: 0x00,
                numberOfTheDiskWithCentralDirectoryStart: 0x00,
                numberOfCentralDirectoryEntriesOnThisDisk: 0x10,
                numberOfCentralDirectoryEntries: 0x10,
                sizeOfCentralDirectory: 0x22,
                centralDirectoryStartOffsetOnDisk: 0x33,
                zipFileComment: 'foo',
            )),
            '504b0506' . // 4 bytes; end of central dir signature 0x06054b50
            '0000' . // 2 bytes; number of this disk
            '0000' . // 2 bytes; number of the disk with the start of the central directory
            '1000' . // 2 bytes; total number of entries in the central directory on this disk
            '1000' . // 2 bytes; total number of entries in the central directory
            '22000000' . // 4 bytes; size of the central directory
            '33000000' . // 4 bytes; offset of start of central directory with respect to the starting disk number
            '0300' . // 2 bytes; .ZIP file comment length
            bin2hex('foo')
        );
    }
}
