<?php

declare(strict_types=1);

namespace ZipStream\Test;

use PHPUnit\Framework\TestCase;
use ZipStream\DataDescriptor;

class DataDescriptorTest extends TestCase
{
    public function testSerializesCorrectly(): void
    {
        $this->assertSame(
            bin2hex(DataDescriptor::generate(
                crc32UncompressedData: 0x11111111,
                compressedSize: 0x77777777,
                uncompressedSize: 0x99999999,
            )),
            '504b0708' . // 4 bytes; Optional data descriptor signature = 0x08074b50
            '11111111' . // 4 bytes; CRC-32 of uncompressed data
            '77777777' . // 4 bytes; Compressed size
            '99999999' // 4 bytes; Uncompressed size
        );
    }
}
