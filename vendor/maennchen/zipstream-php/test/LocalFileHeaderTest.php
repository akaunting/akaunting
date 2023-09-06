<?php

declare(strict_types=1);

namespace ZipStream\Test;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ZipStream\CompressionMethod;
use ZipStream\LocalFileHeader;

class LocalFileHeaderTest extends TestCase
{
    public function testSerializesCorrectly(): void
    {
        $dateTime = new DateTimeImmutable('2022-01-01 01:01:01Z');

        $header = LocalFileHeader::generate(
            versionNeededToExtract: 0x002D,
            generalPurposeBitFlag: 0x2222,
            compressionMethod: CompressionMethod::DEFLATE,
            lastModificationDateTime: $dateTime,
            crc32UncompressedData: 0x11111111,
            compressedSize: 0x77777777,
            uncompressedSize: 0x99999999,
            fileName: 'test.png',
            extraField: 'some content'
        );

        $this->assertSame(
            bin2hex((string) $header),
            '504b0304' . // 4 bytes; Local file header signature
            '2d00' . // 2 bytes; Version needed to extract (minimum)
            '2222' . // 2 bytes; General purpose bit flag
            '0800' . // 2 bytes; Compression method; e.g. none = 0, DEFLATE = 8
            '2008' . // 2 bytes; File last modification time
            '2154' . // 2 bytes; File last modification date
            '11111111' . // 4 bytes; CRC-32 of uncompressed data
            '77777777' . // 4 bytes; Compressed size (or 0xffffffff for ZIP64)
            '99999999' . // 4 bytes; Uncompressed size (or 0xffffffff for ZIP64)
            '0800' . // 2 bytes; File name length (n)
            '0c00' . // 2 bytes; Extra field length (m)
            '746573742e706e67' . // n bytes; File name
            '736f6d6520636f6e74656e74' // m bytes; Extra field
        );
    }
}
