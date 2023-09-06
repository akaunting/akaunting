<?php

declare(strict_types=1);

namespace ZipStream\Test\Zip64;

use PHPUnit\Framework\TestCase;
use ZipStream\Zip64\EndOfCentralDirectoryLocator;

class EndOfCentralDirectoryLocatorTest extends TestCase
{
    public function testSerializesCorrectly(): void
    {
        $descriptor = EndOfCentralDirectoryLocator::generate(
            numberOfTheDiskWithZip64CentralDirectoryStart: 0x11111111,
            zip64centralDirectoryStartOffsetOnDisk: (0x22222222 << 32) + 0x33333333,
            totalNumberOfDisks: 0x44444444,
        );

        $this->assertSame(
            bin2hex($descriptor),
            '504b0607' . // 4 bytes; zip64 end of central dir locator signature - 0x07064b50
            '11111111' . // 4 bytes; number of the disk with the start of the zip64 end of central directory
            '3333333322222222' . // 28 bytes; relative offset of the zip64 end of central directory record
            '44444444' // 4 bytes;total number of disks
        );
    }
}
