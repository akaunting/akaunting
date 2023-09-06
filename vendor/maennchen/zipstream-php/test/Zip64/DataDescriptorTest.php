<?php

declare(strict_types=1);

namespace ZipStream\Test\Zip64;

use PHPUnit\Framework\TestCase;
use ZipStream\Zip64\DataDescriptor;

class DataDescriptorTest extends TestCase
{
    public function testSerializesCorrectly(): void
    {
        $descriptor = DataDescriptor::generate(
            crc32UncompressedData: 0x11111111,
            compressedSize: (0x77777777 << 32) +  0x66666666,
            uncompressedSize: (0x99999999 << 32) + 0x88888888,
        );

        $this->assertSame(
            bin2hex($descriptor),
            '504b0708' . // 4 bytes; Optional data descriptor signature = 0x08074b50
            '11111111' . // 4 bytes; CRC-32 of uncompressed data
            '6666666677777777' . // 8 bytes; Compressed size
            '8888888899999999' // 8 bytes; Uncompressed size
        );
    }
}
