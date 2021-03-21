<?php
declare(strict_types=1);

namespace ZipStream\Option;

final class Archive
{
    const DEFAULT_DEFLATE_LEVEL = 6;
    /**
     * @var string
     */
    private $comment = '';
    /**
     * Size, in bytes, of the largest file to try
     * and load into memory (used by
     * addFileFromPath()).  Large files may also
     * be compressed differently; see the
     * 'largeFileMethod' option. Default is ~20 Mb.
     *
     * @var int
     */
    private $largeFileSize = 20 * 1024 * 1024;
    /**
     * How to handle large files.  Legal values are
     * Method::STORE() (the default), or
     * Method::DEFLATE(). STORE sends the file
     * raw and is significantly
     * faster, while DEFLATE compresses the file
     * and is much, much slower. Note that DEFLATE
     * must compress the file twice and is extremely slow.
     *
     * @var Method
     */
    private $largeFileMethod;
    /**
     * Boolean indicating whether or not to send
     * the HTTP headers for this file.
     *
     * @var bool
     */
    private $sendHttpHeaders = false;
    /**
     * The method called to send headers
     *
     * @var Callable
     */
    private $httpHeaderCallback = 'header';
    /**
     * Enable Zip64 extension, supporting very large
     * archives (any size > 4 GB or file count > 64k)
     *
     * @var bool
     */
    private $enableZip64 = true;
    /**
     * Enable streaming files with single read where
     * general purpose bit 3 indicates local file header
     * contain zero values in crc and size fields,
     * these appear only after file contents
     * in data descriptor block.
     *
     * @var bool
     */
    private $zeroHeader = false;
    /**
     * Enable reading file stat for determining file size.
     * When a 32-bit system reads file size that is
     * over 2 GB, invalid value appears in file size
     * due to integer overflow. Should be disabled on
     * 32-bit systems with method addFileFromPath
     * if any file may exceed 2 GB. In this case file
     * will be read in blocks and correct size will be
     * determined from content.
     *
     * @var bool
     */
    private $statFiles = true;
    /**
     * Enable flush after every write to output stream.
     * @var bool
     */
    private $flushOutput = false;
    /**
     * HTTP Content-Disposition.  Defaults to
     * 'attachment', where
     * FILENAME is the specified filename.
     *
     * Note that this does nothing if you are
     * not sending HTTP headers.
     *
     * @var string
     */
    private $contentDisposition = 'attachment';
    /**
     * Note that this does nothing if you are
     * not sending HTTP headers.
     *
     * @var string
     */
    private $contentType = 'application/x-zip';
    /**
     * @var int
     */
    private $deflateLevel = 6;

    /**
     * @var resource
     */
    private $outputStream;

    /**
     * Options constructor.
     */
    public function __construct()
    {
        $this->largeFileMethod = Method::STORE();
        $this->outputStream = fopen('php://output', 'wb');
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function getLargeFileSize(): int
    {
        return $this->largeFileSize;
    }

    public function setLargeFileSize(int $largeFileSize): void
    {
        $this->largeFileSize = $largeFileSize;
    }

    public function getLargeFileMethod(): Method
    {
        return $this->largeFileMethod;
    }

    public function setLargeFileMethod(Method $largeFileMethod): void
    {
        $this->largeFileMethod = $largeFileMethod;
    }

    public function isSendHttpHeaders(): bool
    {
        return $this->sendHttpHeaders;
    }

    public function setSendHttpHeaders(bool $sendHttpHeaders): void
    {
        $this->sendHttpHeaders = $sendHttpHeaders;
    }

    public function getHttpHeaderCallback(): Callable
    {
        return $this->httpHeaderCallback;
    }

    public function setHttpHeaderCallback(Callable $httpHeaderCallback): void
    {
        $this->httpHeaderCallback = $httpHeaderCallback;
    }

    public function isEnableZip64(): bool
    {
        return $this->enableZip64;
    }

    public function setEnableZip64(bool $enableZip64): void
    {
        $this->enableZip64 = $enableZip64;
    }

    public function isZeroHeader(): bool
    {
        return $this->zeroHeader;
    }

    public function setZeroHeader(bool $zeroHeader): void
    {
        $this->zeroHeader = $zeroHeader;
    }

    public function isFlushOutput(): bool
    {
        return $this->flushOutput;
    }

    public function setFlushOutput(bool $flushOutput): void
    {
        $this->flushOutput = $flushOutput;
    }

    public function isStatFiles(): bool
    {
        return $this->statFiles;
    }

    public function setStatFiles(bool $statFiles): void
    {
        $this->statFiles = $statFiles;
    }

    public function getContentDisposition(): string
    {
        return $this->contentDisposition;
    }

    public function setContentDisposition(string $contentDisposition): void
    {
        $this->contentDisposition = $contentDisposition;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    /**
     * @return resource
     */
    public function getOutputStream()
    {
        return $this->outputStream;
    }

    /**
     * @param resource $outputStream
     */
    public function setOutputStream($outputStream): void
    {
        $this->outputStream = $outputStream;
    }

    /**
     * @return int
     */
    public function getDeflateLevel(): int
    {
        return $this->deflateLevel;
    }

    /**
     * @param int $deflateLevel
     */
    public function setDeflateLevel(int $deflateLevel): void
    {
        $this->deflateLevel = $deflateLevel;
    }
}
