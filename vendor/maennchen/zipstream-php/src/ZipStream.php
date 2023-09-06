<?php

declare(strict_types=1);

namespace ZipStream;

use Closure;
use DateTimeImmutable;
use DateTimeInterface;
use GuzzleHttp\Psr7\StreamWrapper;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use ZipStream\Exception\FileNotFoundException;
use ZipStream\Exception\FileNotReadableException;
use ZipStream\Exception\OverflowException;
use ZipStream\Exception\ResourceActionException;

/**
 * Streamed, dynamically generated zip archives.
 *
 * ## Usage
 *
 * Streaming zip archives is a simple, three-step process:
 *
 * 1.  Create the zip stream:
 *
 * ```php
 * $zip = new ZipStream(outputName: 'example.zip');
 * ```
 *
 * 2.  Add one or more files to the archive:
 *
 * ```php
 * // add first file
 * $zip->addFile(fileName: 'world.txt', data: 'Hello World');
 *
 * // add second file
 * $zip->addFile(fileName: 'moon.txt', data: 'Hello Moon');
 * ```
 *
 * 3.  Finish the zip stream:
 *
 * ```php
 * $zip->finish();
 * ```
 *
 * You can also add an archive comment, add comments to individual files,
 * and adjust the timestamp of files. See the API documentation for each
 * method below for additional information.
 *
 * ## Example
 *
 * ```php
 * // create a new zip stream object
 * $zip = new ZipStream(outputName: 'some_files.zip');
 *
 * // list of local files
 * $files = array('foo.txt', 'bar.jpg');
 *
 * // read and add each file to the archive
 * foreach ($files as $path)
 *   $zip->addFileFormPath(fileName: $path, $path);
 *
 * // write archive footer to stream
 * $zip->finish();
 * ```
 */
class ZipStream
{
    /**
     * This number corresponds to the ZIP version/OS used (2 bytes)
     * From: https://www.iana.org/assignments/media-types/application/zip
     * The upper byte (leftmost one) indicates the host system (OS) for the
     * file.  Software can use this information to determine
     * the line record format for text files etc.  The current
     * mappings are:
     *
     * 0 - MS-DOS and OS/2 (F.A.T. file systems)
     * 1 - Amiga                     2 - VAX/VMS
     * 3 - *nix                      4 - VM/CMS
     * 5 - Atari ST                  6 - OS/2 H.P.F.S.
     * 7 - Macintosh                 8 - Z-System
     * 9 - CP/M                      10 thru 255 - unused
     *
     * The lower byte (rightmost one) indicates the version number of the
     * software used to encode the file.  The value/10
     * indicates the major version number, and the value
     * mod 10 is the minor version number.
     * Here we are using 6 for the OS, indicating OS/2 H.P.F.S.
     * to prevent file permissions issues upon extract (see #84)
     * 0x603 is 00000110 00000011 in binary, so 6 and 3
     *
     * @internal
     */
    public const ZIP_VERSION_MADE_BY = 0x603;

    private bool $ready = true;

    private int $offset = 0;

    /**
     * @var string[]
     */
    private array $centralDirectoryRecords = [];

    /**
     * @var resource
     */
    private $outputStream;

    private readonly Closure $httpHeaderCallback;

    /**
     * @var File[]
     */
    private array $recordedSimulation = [];

    /**
     * Create a new ZipStream object.
     *
     * ##### Examples
     *
     * ```php
     * // create a new zip file named 'foo.zip'
     * $zip = new ZipStream(outputName: 'foo.zip');
     *
     * // create a new zip file named 'bar.zip' with a comment
     * $zip = new ZipStream(
     *   outputName: 'bar.zip',
     *   comment: 'this is a comment for the zip file.',
     * );
     * ```
     *
     * @param OperationMode $operationMode
     * The mode can be used to switch between `NORMAL` and `SIMULATION_*` modes.
     * For details see the `OperationMode` documentation.
     *
     * Default to `NORMAL`.
     *
     * @param string $comment
     * Archive Level Comment
     *
     * @param StreamInterface|resource|null $outputStream
     * Override the output of the archive to a different target.
     *
     * By default the archive is sent to `STDOUT`.
     *
     * @param CompressionMethod $defaultCompressionMethod
     * How to handle file compression. Legal values are
     * `CompressionMethod::DEFLATE` (the default), or
     * `CompressionMethod::STORE`. `STORE` sends the file raw and is
     * significantly faster, while `DEFLATE` compresses the file and
     * is much, much slower.
     *
     * @param int $defaultDeflateLevel
     * Default deflation level. Only relevant if `compressionMethod`
     * is `DEFLATE`.
     *
     * See details of [`deflate_init`](https://www.php.net/manual/en/function.deflate-init.php#refsect1-function.deflate-init-parameters)
     *
     * @param bool $enableZip64
     * Enable Zip64 extension, supporting very large
     * archives (any size > 4 GB or file count > 64k)
     *
     * @param bool $defaultEnableZeroHeader
     * Enable streaming files with single read.
     *
     * When the zero header is set, the file is streamed into the output
     * and the size & checksum are added at the end of the file. This is the
     * fastest method and uses the least memory. Unfortunately not all
     * ZIP clients fully support this and can lead to clients reporting
     * the generated ZIP files as corrupted in combination with other
     * circumstances. (Zip64 enabled, using UTF8 in comments / names etc.)
     *
     * When the zero header is not set, the length & checksum need to be
     * defined before the file is actually added. To prevent loading all
     * the data into memory, the data has to be read twice. If the data
     * which is added is not seekable, this call will fail.
     *
     * @param bool $sendHttpHeaders
     * Boolean indicating whether or not to send
     * the HTTP headers for this file.
     *
     * @param ?Closure $httpHeaderCallback
     * The method called to send HTTP headers
     *
     * @param string|null $outputName
     * The name of the created archive.
     *
     * Only relevant if `$sendHttpHeaders = true`.
     *
     * @param string $contentDisposition
     * HTTP Content-Disposition
     *
     * Only relevant if `sendHttpHeaders = true`.
     *
     * @param string $contentType
     * HTTP Content Type
     *
     * Only relevant if `sendHttpHeaders = true`.
     *
     * @param bool $flushOutput
     * Enable flush after every write to output stream.
     *
     * @return self
     */
    public function __construct(
        private OperationMode $operationMode = OperationMode::NORMAL,
        private readonly string $comment = '',
        $outputStream = null,
        private readonly CompressionMethod $defaultCompressionMethod = CompressionMethod::DEFLATE,
        private readonly int $defaultDeflateLevel = 6,
        private readonly bool $enableZip64 = true,
        private readonly bool $defaultEnableZeroHeader = true,
        private bool $sendHttpHeaders = true,
        ?Closure $httpHeaderCallback = null,
        private readonly ?string $outputName = null,
        private readonly string $contentDisposition = 'attachment',
        private readonly string $contentType = 'application/x-zip',
        private bool $flushOutput = false,
    ) {
        $this->outputStream = self::normalizeStream($outputStream);
        $this->httpHeaderCallback = $httpHeaderCallback ?? header(...);
    }

    /**
     * Add a file to the archive.
     *
     * ##### File Options
     *
     * See {@see addFileFromPsr7Stream()}
     *
     * ##### Examples
     *
     * ```php
     * // add a file named 'world.txt'
     * $zip->addFile(fileName: 'world.txt', data: 'Hello World!');
     *
     * // add a file named 'bar.jpg' with a comment and a last-modified
     * // time of two hours ago
     * $zip->addFile(
     *   fileName: 'bar.jpg',
     *   data: $data,
     *   comment: 'this is a comment about bar.jpg',
     *   lastModificationDateTime: new DateTime('2 hours ago'),
     * );
     * ```
     *
     * @param string $data
     *
     * contents of file
     */
    public function addFile(
        string $fileName,
        string $data,
        string $comment = '',
        ?CompressionMethod $compressionMethod = null,
        ?int $deflateLevel = null,
        ?DateTimeInterface $lastModificationDateTime = null,
        ?int $maxSize = null,
        ?int $exactSize = null,
        ?bool $enableZeroHeader = null,
    ): void {
        $this->addFileFromCallback(
            fileName: $fileName,
            callback: fn () => $data,
            comment: $comment,
            compressionMethod: $compressionMethod,
            deflateLevel: $deflateLevel,
            lastModificationDateTime: $lastModificationDateTime,
            maxSize: $maxSize,
            exactSize: $exactSize,
            enableZeroHeader: $enableZeroHeader,
        );
    }

    /**
     * Add a file at path to the archive.
     *
     * ##### File Options
     *
     * See {@see addFileFromPsr7Stream()}
     *
     * ###### Examples
     *
     * ```php
     * // add a file named 'foo.txt' from the local file '/tmp/foo.txt'
     * $zip->addFileFromPath(
     *   fileName: 'foo.txt',
     *   path: '/tmp/foo.txt',
     * );
     *
     * // add a file named 'bigfile.rar' from the local file
     * // '/usr/share/bigfile.rar' with a comment and a last-modified
     * // time of two hours ago
     * $zip->addFile(
     *   fileName: 'bigfile.rar',
     *   path: '/usr/share/bigfile.rar',
     *   comment: 'this is a comment about bigfile.rar',
     *   lastModificationDateTime: new DateTime('2 hours ago'),
     * );
     * ```
     *
     * @throws \ZipStream\Exception\FileNotFoundException
     * @throws \ZipStream\Exception\FileNotReadableException
     */
    public function addFileFromPath(
        /**
         * name of file in archive (including directory path).
         */
        string $fileName,

        /**
         * path to file on disk (note: paths should be encoded using
         * UNIX-style forward slashes -- e.g '/path/to/some/file').
         */
        string $path,
        string $comment = '',
        ?CompressionMethod $compressionMethod = null,
        ?int $deflateLevel = null,
        ?DateTimeInterface $lastModificationDateTime = null,
        ?int $maxSize = null,
        ?int $exactSize = null,
        ?bool $enableZeroHeader = null,
    ): void {
        if (!is_readable($path)) {
            if (!file_exists($path)) {
                throw new FileNotFoundException($path);
            }
            throw new FileNotReadableException($path);
        }

        if ($fileTime = filemtime($path)) {
            $lastModificationDateTime ??= (new DateTimeImmutable())->setTimestamp($fileTime);
        }

        $this->addFileFromCallback(
            fileName: $fileName,
            callback: function () use ($path) {

                $stream =  fopen($path, 'rb');

                if (!$stream) {
                    // @codeCoverageIgnoreStart
                    throw new ResourceActionException('fopen');
                    // @codeCoverageIgnoreEnd
                }

                return $stream;
            },
            comment: $comment,
            compressionMethod: $compressionMethod,
            deflateLevel: $deflateLevel,
            lastModificationDateTime: $lastModificationDateTime,
            maxSize: $maxSize,
            exactSize: $exactSize,
            enableZeroHeader: $enableZeroHeader,
        );
    }

    /**
     * Add an open stream (resource) to the archive.
     *
     * ##### File Options
     *
     * See {@see addFileFromPsr7Stream()}
     *
     * ##### Examples
     *
     * ```php
     * // create a temporary file stream and write text to it
     * $filePointer = tmpfile();
     * fwrite($filePointer, 'The quick brown fox jumped over the lazy dog.');
     *
     * // add a file named 'streamfile.txt' from the content of the stream
     * $archive->addFileFromStream(
     *   fileName: 'streamfile.txt',
     *   stream: $filePointer,
     * );
     * ```
     *
     * @param resource $stream contents of file as a stream resource
     */
    public function addFileFromStream(
        string $fileName,
        $stream,
        string $comment = '',
        ?CompressionMethod $compressionMethod = null,
        ?int $deflateLevel = null,
        ?DateTimeInterface $lastModificationDateTime = null,
        ?int $maxSize = null,
        ?int $exactSize = null,
        ?bool $enableZeroHeader = null,
    ): void {
        $this->addFileFromCallback(
            fileName: $fileName,
            callback: fn () => $stream,
            comment: $comment,
            compressionMethod: $compressionMethod,
            deflateLevel: $deflateLevel,
            lastModificationDateTime: $lastModificationDateTime,
            maxSize: $maxSize,
            exactSize: $exactSize,
            enableZeroHeader: $enableZeroHeader,
        );
    }

    /**
     * Add an open stream to the archive.
     *
     * ##### Examples
     *
     * ```php
     * $stream = $response->getBody();
     * // add a file named 'streamfile.txt' from the content of the stream
     * $archive->addFileFromPsr7Stream(
     *   fileName: 'streamfile.txt',
     *   stream: $stream,
     * );
     * ```
     *
     * @param string $fileName
     * path of file in archive (including directory)
     *
     * @param StreamInterface $stream
     * contents of file as a stream resource
     *
     * @param string $comment
     * ZIP comment for this file
     *
     * @param ?CompressionMethod $compressionMethod
     * Override `defaultCompressionMethod`
     *
     * See {@see __construct()}
     *
     * @param ?int $deflateLevel
     * Override `defaultDeflateLevel`
     *
     * See {@see __construct()}
     *
     * @param ?DateTimeInterface $lastModificationDateTime
     * Set last modification time of file.
     *
     * Default: `now`
     *
     * @param ?int $maxSize
     * Only read `maxSize` bytes from file.
     *
     * The file is considered done when either reaching `EOF`
     * or the `maxSize`.
     *
     * @param ?int $exactSize
     * Read exactly `exactSize` bytes from file.
     * If `EOF` is reached before reading `exactSize` bytes, an error will be
     * thrown. The parameter allows for faster size calculations if the `stream`
     * does not support `fstat` size or is slow and otherwise known beforehand.
     *
     * @param ?bool $enableZeroHeader
     * Override `defaultEnableZeroHeader`
     *
     * See {@see __construct()}
     */
    public function addFileFromPsr7Stream(
        string $fileName,
        StreamInterface $stream,
        string $comment = '',
        ?CompressionMethod $compressionMethod = null,
        ?int $deflateLevel = null,
        ?DateTimeInterface $lastModificationDateTime = null,
        ?int $maxSize = null,
        ?int $exactSize = null,
        ?bool $enableZeroHeader = null,
    ): void {
        $this->addFileFromCallback(
            fileName: $fileName,
            callback: fn () => $stream,
            comment: $comment,
            compressionMethod: $compressionMethod,
            deflateLevel: $deflateLevel,
            lastModificationDateTime: $lastModificationDateTime,
            maxSize: $maxSize,
            exactSize: $exactSize,
            enableZeroHeader: $enableZeroHeader,
        );
    }

    /**
     * Add a file based on a callback.
     *
     * This is useful when you want to simulate a lot of files without keeping
     * all of the file handles open at the same time.
     *
     * ##### Examples
     *
     * ```php
     * foreach($files as $name => $size) {
     *   $archive->addFileFromPsr7Stream(
     *     fileName: 'streamfile.txt',
     *     exactSize: $size,
     *     callback: function() use($name): Psr\Http\Message\StreamInterface {
     *       $response = download($name);
     *       return $response->getBody();
     *     }
     *   );
     * }
     * ```
     *
     * @param string $fileName
     * path of file in archive (including directory)
     *
     * @param Closure $callback
     * @psalm-param Closure(): (resource|StreamInterface|string) $callback
     * A callback to get the file contents in the shape of a PHP stream,
     * a Psr StreamInterface implementation, or a string.
     *
     * @param string $comment
     * ZIP comment for this file
     *
     * @param ?CompressionMethod $compressionMethod
     * Override `defaultCompressionMethod`
     *
     * See {@see __construct()}
     *
     * @param ?int $deflateLevel
     * Override `defaultDeflateLevel`
     *
     * See {@see __construct()}
     *
     * @param ?DateTimeInterface $lastModificationDateTime
     * Set last modification time of file.
     *
     * Default: `now`
     *
     * @param ?int $maxSize
     * Only read `maxSize` bytes from file.
     *
     * The file is considered done when either reaching `EOF`
     * or the `maxSize`.
     *
     * @param ?int $exactSize
     * Read exactly `exactSize` bytes from file.
     * If `EOF` is reached before reading `exactSize` bytes, an error will be
     * thrown. The parameter allows for faster size calculations if the `stream`
     * does not support `fstat` size or is slow and otherwise known beforehand.
     *
     * @param ?bool $enableZeroHeader
     * Override `defaultEnableZeroHeader`
     *
     * See {@see __construct()}
     */
    public function addFileFromCallback(
        string $fileName,
        Closure $callback,
        string $comment = '',
        ?CompressionMethod $compressionMethod = null,
        ?int $deflateLevel = null,
        ?DateTimeInterface $lastModificationDateTime = null,
        ?int $maxSize = null,
        ?int $exactSize = null,
        ?bool $enableZeroHeader = null,
    ): void {
        $file = new File(
            dataCallback: function () use ($callback, $maxSize) {
                $data = $callback();

                if(is_resource($data)) {
                    return $data;
                }

                if($data instanceof StreamInterface) {
                    return StreamWrapper::getResource($data);
                }


                $stream = fopen('php://memory', 'rw+');
                if ($stream === false) {
                    // @codeCoverageIgnoreStart
                    throw new ResourceActionException('fopen');
                    // @codeCoverageIgnoreEnd
                }
                if ($maxSize !== null && fwrite($stream, $data, $maxSize) === false) {
                    // @codeCoverageIgnoreStart
                    throw new ResourceActionException('fwrite', $stream);
                    // @codeCoverageIgnoreEnd
                } elseif (fwrite($stream, $data) === false) {
                    // @codeCoverageIgnoreStart
                    throw new ResourceActionException('fwrite', $stream);
                    // @codeCoverageIgnoreEnd
                }
                if (rewind($stream) === false) {
                    // @codeCoverageIgnoreStart
                    throw new ResourceActionException('rewind', $stream);
                    // @codeCoverageIgnoreEnd
                }

                return $stream;

            },
            send: $this->send(...),
            recordSentBytes: $this->recordSentBytes(...),
            operationMode: $this->operationMode,
            fileName: $fileName,
            startOffset: $this->offset,
            compressionMethod: $compressionMethod ?? $this->defaultCompressionMethod,
            comment: $comment,
            deflateLevel: $deflateLevel ?? $this->defaultDeflateLevel,
            lastModificationDateTime: $lastModificationDateTime ?? new DateTimeImmutable(),
            maxSize: $maxSize,
            exactSize: $exactSize,
            enableZip64: $this->enableZip64,
            enableZeroHeader: $enableZeroHeader ?? $this->defaultEnableZeroHeader,
        );

        if($this->operationMode !== OperationMode::NORMAL) {
            $this->recordedSimulation[] = $file;
        }

        $this->centralDirectoryRecords[] = $file->process();
    }

    /**
     * Add a directory to the archive.
     *
     * ##### File Options
     *
     * See {@see addFileFromPsr7Stream()}
     *
     * ##### Examples
     *
     * ```php
     * // add a directory named 'world/'
     * $zip->addFile(fileName: 'world/');
     * ```
     */
    public function addDirectory(
        string $fileName,
        string $comment = '',
        ?DateTimeInterface $lastModificationDateTime = null,
    ): void {
        if (!str_ends_with($fileName, '/')) {
            $fileName .= '/';
        }

        $this->addFile(
            fileName: $fileName,
            data: '',
            comment: $comment,
            compressionMethod: CompressionMethod::STORE,
            deflateLevel: null,
            lastModificationDateTime: $lastModificationDateTime,
            maxSize: 0,
            exactSize: 0,
            enableZeroHeader: false,
        );
    }

    /**
     * Executes a previously calculated simulation.
     *
     * ##### Example
     *
     * ```php
     * $zip = new ZipStream(
     *   outputName: 'foo.zip',
     *   operationMode: OperationMode::SIMULATE_STRICT,
     * );
     *
     * $zip->addFile('test.txt', 'Hello World');
     *
     * $size = $zip->finish();
     *
     * header('Content-Length: '. $size);
     *
     * $zip->executeSimulation();
     * ```
     */
    public function executeSimulation(): void
    {
        if($this->operationMode !== OperationMode::NORMAL) {
            throw new RuntimeException('Zip simulation is not finished.');
        }

        foreach($this->recordedSimulation as $file) {
            $this->centralDirectoryRecords[] = $file->cloneSimulationExecution()->process();
        }

        $this->finish();
    }

    /**
     * Write zip footer to stream.
     *
     * The clase is left in an unusable state after `finish`.
     *
     * ##### Example
     *
     * ```php
     * // write footer to stream
     * $zip->finish();
     * ```
     */
    public function finish(): int
    {
        $centralDirectoryStartOffsetOnDisk = $this->offset;
        $sizeOfCentralDirectory = 0;

        // add trailing cdr file records
        foreach ($this->centralDirectoryRecords as $centralDirectoryRecord) {
            $this->send($centralDirectoryRecord);
            $sizeOfCentralDirectory += strlen($centralDirectoryRecord);
        }

        // Add 64bit headers (if applicable)
        if (count($this->centralDirectoryRecords) >= 0xFFFF ||
            $centralDirectoryStartOffsetOnDisk > 0xFFFFFFFF ||
            $sizeOfCentralDirectory > 0xFFFFFFFF) {
            if (!$this->enableZip64) {
                throw new OverflowException();
            }

            $this->send(Zip64\EndOfCentralDirectory::generate(
                versionMadeBy: self::ZIP_VERSION_MADE_BY,
                versionNeededToExtract: Version::ZIP64->value,
                numberOfThisDisk: 0,
                numberOfTheDiskWithCentralDirectoryStart: 0,
                numberOfCentralDirectoryEntriesOnThisDisk: count($this->centralDirectoryRecords),
                numberOfCentralDirectoryEntries: count($this->centralDirectoryRecords),
                sizeOfCentralDirectory: $sizeOfCentralDirectory,
                centralDirectoryStartOffsetOnDisk: $centralDirectoryStartOffsetOnDisk,
                extensibleDataSector: '',
            ));

            $this->send(Zip64\EndOfCentralDirectoryLocator::generate(
                numberOfTheDiskWithZip64CentralDirectoryStart: 0x00,
                zip64centralDirectoryStartOffsetOnDisk: $centralDirectoryStartOffsetOnDisk + $sizeOfCentralDirectory,
                totalNumberOfDisks: 1,
            ));
        }

        // add trailing cdr eof record
        $numberOfCentralDirectoryEntries = min(count($this->centralDirectoryRecords), 0xFFFF);
        $this->send(EndOfCentralDirectory::generate(
            numberOfThisDisk: 0x00,
            numberOfTheDiskWithCentralDirectoryStart: 0x00,
            numberOfCentralDirectoryEntriesOnThisDisk: $numberOfCentralDirectoryEntries,
            numberOfCentralDirectoryEntries: $numberOfCentralDirectoryEntries,
            sizeOfCentralDirectory: min($sizeOfCentralDirectory, 0xFFFFFFFF),
            centralDirectoryStartOffsetOnDisk: min($centralDirectoryStartOffsetOnDisk, 0xFFFFFFFF),
            zipFileComment: $this->comment,
        ));

        $size = $this->offset;

        // The End
        $this->clear();

        return $size;
    }

    /**
     * @param StreamInterface|resource|null $outputStream
     * @return resource
     */
    private static function normalizeStream($outputStream)
    {
        if ($outputStream instanceof StreamInterface) {
            return StreamWrapper::getResource($outputStream);
        }
        if (is_resource($outputStream)) {
            return $outputStream;
        }
        return fopen('php://output', 'wb');
    }

    /**
     * Record sent bytes
     */
    private function recordSentBytes(int $sentBytes): void
    {
        $this->offset += $sentBytes;
    }

    /**
     * Send string, sending HTTP headers if necessary.
     * Flush output after write if configure option is set.
     */
    private function send(string $data): void
    {
        if (!$this->ready) {
            throw new RuntimeException('Archive is already finished');
        }

        if ($this->operationMode === OperationMode::NORMAL && $this->sendHttpHeaders) {
            $this->sendHttpHeaders();
            $this->sendHttpHeaders = false;
        }

        $this->recordSentBytes(strlen($data));

        if ($this->operationMode === OperationMode::NORMAL) {
            if (fwrite($this->outputStream, $data) === false) {
                throw new ResourceActionException('fwrite', $this->outputStream);
            }

            if ($this->flushOutput) {
                // flush output buffer if it is on and flushable
                $status = ob_get_status();
                if (isset($status['flags']) && is_int($status['flags']) && ($status['flags'] & PHP_OUTPUT_HANDLER_FLUSHABLE)) {
                    ob_flush();
                }

                // Flush system buffers after flushing userspace output buffer
                flush();
            }
        }
    }

     /**
     * Send HTTP headers for this stream.
     */
    private function sendHttpHeaders(): void
    {
        // grab content disposition
        $disposition = $this->contentDisposition;

        if ($this->outputName) {
            // Various different browsers dislike various characters here. Strip them all for safety.
            $safeOutput = trim(str_replace(['"', "'", '\\', ';', "\n", "\r"], '', $this->outputName));

            // Check if we need to UTF-8 encode the filename
            $urlencoded = rawurlencode($safeOutput);
            $disposition .= "; filename*=UTF-8''{$urlencoded}";
        }

        $headers = [
            'Content-Type' => $this->contentType,
            'Content-Disposition' => $disposition,
            'Pragma' => 'public',
            'Cache-Control' => 'public, must-revalidate',
            'Content-Transfer-Encoding' => 'binary',
        ];

        foreach ($headers as $key => $val) {
            ($this->httpHeaderCallback)("$key: $val");
        }
    }

    /**
     * Clear all internal variables. Note that the stream object is not
     * usable after this.
     */
    private function clear(): void
    {
        $this->centralDirectoryRecords = [];
        $this->offset = 0;

        if($this->operationMode === OperationMode::NORMAL) {
            $this->ready = false;
            $this->recordedSimulation = [];
        } else {
            $this->operationMode = OperationMode::NORMAL;
        }
    }
}
