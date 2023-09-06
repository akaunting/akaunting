<?php

declare(strict_types=1);

namespace ZipStream\Test\Zip64;

use PHPUnit\Framework\TestCase;
use ZipStream\Zip64\ExtendedInformationExtraField;

class ExtendedInformationExtraFieldTest extends TestCase
{
    public function testSerializesCorrectly(): void
    {
        $extraField = ExtendedInformationExtraField::generate(
            originalSize: (0x77777777 << 32) + 0x66666666,
            compressedSize: (0x99999999 << 32) + 0x88888888,
            relativeHeaderOffset: (0x22222222 << 32) + 0x11111111,
            diskStartNumber: 0x33333333,
        );

        $this->assertSame(
            bin2hex($extraField),
            '0100' . // 2 bytes; Tag for this "extra" block type
            '1c00' . // 2 bytes; Size of this "extra" block
            '6666666677777777' . // 8 bytes; Original uncompressed file size
            '8888888899999999' . // 8 bytes; Size of compressed data
            '1111111122222222' . // 8 bytes; Offset of local header record
            '33333333' // 4 bytes; Number of the disk on which this file starts
        );
    }

    public function testSerializesEmptyCorrectly(): void
    {
        $extraField = ExtendedInformationExtraField::generate();

        $this->assertSame(
            bin2hex($extraField),
            '0100' . // 2 bytes; Tag for this "extra" block type
            '0000' // 2 bytes; Size of this "extra" block
        );
    }
}
