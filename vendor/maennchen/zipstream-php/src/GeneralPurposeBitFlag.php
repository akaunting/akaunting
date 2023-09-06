<?php

declare(strict_types=1);

namespace ZipStream;

/**
 * @internal
 */
abstract class GeneralPurposeBitFlag
{
    /**
     * If set, indicates that the file is encrypted.
     */
    public const ENCRYPTED = 1 << 0;

    /**
     * (For Methods 8 and 9 - Deflating)
     * Normal (-en) compression option was used.
     */
    public const DEFLATE_COMPRESSION_NORMAL = 0 << 1;

    /**
     * (For Methods 8 and 9 - Deflating)
     * Maximum (-exx/-ex) compression option was used.
     */
    public const DEFLATE_COMPRESSION_MAXIMUM = 1 << 1;

    /**
     * (For Methods 8 and 9 - Deflating)
     * Fast (-ef) compression option was used.
     */
    public const DEFLATE_COMPRESSION_FAST = 10 << 1;

    /**
     * (For Methods 8 and 9 - Deflating)
     * Super Fast (-es) compression option was used.
     */
    public const DEFLATE_COMPRESSION_SUPERFAST = 11 << 1;

    /**
     * If the compression method used was type 14,
     * LZMA, then this bit, if set, indicates
     * an end-of-stream (EOS) marker is used to
     * mark the end of the compressed data stream.
     * If clear, then an EOS marker is not present
     * and the compressed data size must be known
     * to extract.
     */
    public const LZMA_EOS = 1 << 1;

    /**
     * If this bit is set, the fields crc-32, compressed
     * size and uncompressed size are set to zero in the
     * local header.  The correct values are put in the
     * data descriptor immediately following the compressed
     * data.
     */
    public const ZERO_HEADER = 1 << 3;

    /**
     * If this bit is set, this indicates that the file is
     * compressed patched data.
     */
    public const COMPRESSED_PATCHED_DATA = 1 << 5;

    /**
     * Strong encryption. If this bit is set, you MUST
     * set the version needed to extract value to at least
     * 50 and you MUST also set bit 0.  If AES encryption
     * is used, the version needed to extract value MUST
     * be at least 51.
     */
    public const STRONG_ENCRYPTION = 1 << 6;

    /**
     * Language encoding flag (EFS).  If this bit is set,
     * the filename and comment fields for this file
     * MUST be encoded using UTF-8.
     */
    public const EFS = 1 << 11;

    /**
     * Set when encrypting the Central Directory to indicate
     * selected data values in the Local Header are masked to
     * hide their actual values.
     */
    public const ENCRYPT_CENTRAL_DIRECTORY = 1 << 13;
}
