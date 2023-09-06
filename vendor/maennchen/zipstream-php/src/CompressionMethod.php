<?php

declare(strict_types=1);

namespace ZipStream;

enum CompressionMethod: int
{
    /**
     * The file is stored (no compression)
     */
    case STORE = 0x00;

    // 0x01: legacy algorithm - The file is Shrunk
    // 0x02: legacy algorithm - The file is Reduced with compression factor 1
    // 0x03: legacy algorithm - The file is Reduced with compression factor 2
    // 0x04: legacy algorithm - The file is Reduced with compression factor 3
    // 0x05: legacy algorithm - The file is Reduced with compression factor 4
    // 0x06: legacy algorithm - The file is Imploded
    // 0x07: Reserved for Tokenizing compression algorithm

    /**
     * The file is Deflated
     */
    case DEFLATE = 0x08;

    // /**
    //  * Enhanced Deflating using Deflate64(tm)
    //  */
    // case DEFLATE_64 = 0x09;

    // /**
    //  * PKWARE Data Compression Library Imploding (old IBM TERSE)
    //  */
    // case PKWARE = 0x0a;

    // // 0x0b: Reserved by PKWARE

    // /**
    //  * File is compressed using BZIP2 algorithm
    //  */
    // case BZIP2 = 0x0c;

    // // 0x0d: Reserved by PKWARE

    // /**
    //  * LZMA
    //  */
    // case LZMA = 0x0e;

    // // 0x0f: Reserved by PKWARE

    // /**
    //  * IBM z/OS CMPSC Compression
    //  */
    // case IBM_ZOS_CMPSC = 0x10;

    // // 0x11: Reserved by PKWARE

    // /**
    //  * File is compressed using IBM TERSE
    //  */
    // case IBM_TERSE = 0x12;

    // /**
    //  * IBM LZ77 z Architecture
    //  */
    // case IBM_LZ77 = 0x13;

    // // 0x14: deprecated (use method 93 for zstd)

    // /**
    //  * Zstandard (zstd) Compression
    //  */
    // case ZSTD = 0x5d;

    // /**
    //  * MP3 Compression
    //  */
    // case MP3 = 0x5e;

    // /**
    //  * XZ Compression
    //  */
    // case XZ = 0x5f;

    // /**
    //  * JPEG variant
    //  */
    // case JPEG = 0x60;

    // /**
    //  * WavPack compressed data
    //  */
    // case WAV_PACK = 0x61;

    // /**
    //  * PPMd version I, Rev 1
    //  */
    // case PPMD_1_1 = 0x62;

    // /**
    //  * AE-x encryption marker
    //  */
    // case AE_X_ENCRYPTION = 0x63;
}
