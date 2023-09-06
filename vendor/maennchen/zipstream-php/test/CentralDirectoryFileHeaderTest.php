<?php

declare(strict_types=1);

namespace ZipStream\Test;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ZipStream\CentralDirectoryFileHeader;
use ZipStream\CompressionMethod;

class CentralDirectoryFileHeaderTest extends TestCase
{
    public function testSerializesCorrectly(): void
    {
        $dateTime = new DateTimeImmutable('2022-01-01 01:01:01Z');

        $header = CentralDirectoryFileHeader::generate(
            versionMadeBy: 0x603,
            versionNeededToExtract: 0x002D,
            generalPurposeBitFlag: 0x2222,
            compressionMethod: CompressionMethod::DEFLATE,
            lastModificationDateTime: $dateTime,
            crc32: 0x11111111,
            compressedSize: 0x77777777,
            uncompressedSize: 0x99999999,
            fileName: 'test.png',
            extraField: 'some content',
            fileComment: 'some comment',
            diskNumberStart: 0,
            internalFileAttributes: 0,
            externalFileAttributes: 32,
            relativeOffsetOfLocalHeader: 0x1234,
        );

        $this->assertSame(
            bin2hex($header),
            '504b0102' . // 4 bytes; central file header signature
            '0306' . // 2 bytes; version made by
            '2d00' . // 2 bytes; version needed to extract
            '2222' . // 2 bytes; general purpose bit flag
            '0800' . // 2 bytes; compression method
            '2008' . // 2 bytes; last mod file time
            '2154' . // 2 bytes; last mod file date
            '11111111' . // 4 bytes; crc-32
            '77777777' . // 4 bytes; compressed size
            '99999999' . // 4 bytes; uncompressed size
            '0800' . // 2 bytes; file name length (n)
            '0c00' . // 2 bytes; extra field length (m)
            '0c00' . // 2 bytes; file comment length (o)
            '0000' . // 2 bytes; disk number start
            '0000' . // 2 bytes; internal file attributes
            '20000000' . // 4 bytes; external file attributes
            '34120000' . // 4 bytes; relative offset of local header
            '746573742e706e67' . // n bytes; file name
            '736f6d6520636f6e74656e74' . // m bytes; extra field
            '736f6d6520636f6d6d656e74' // o bytes; file comment
        );
    }
}
