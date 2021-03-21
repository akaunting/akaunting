<?php
declare(strict_types=1);

namespace ZipStream;

use Psr\Http\Message\StreamInterface;
use ZipStream\Exception\OverflowException;
use ZipStream\Option\Archive as ArchiveOptions;
use ZipStream\Option\File as FileOptions;
use ZipStream\Option\Version;

/**
 * ZipStream
 *
 * Streamed, dynamically generated zip archives.
 *
 * Usage:
 *
 * Streaming zip archives is a simple, three-step process:
 *
 * 1.  Create the zip stream:
 *
 *     $zip = new ZipStream('example.zip');
 *
 * 2.  Add one or more files to the archive:
 *
 *      * add first file
 *     $data = file_get_contents('some_file.gif');
 *     $zip->addFile('some_file.gif', $data);
 *
 *      * add second file
 *     $data = file_get_contents('some_file.gif');
 *     $zip->addFile('another_file.png', $data);
 *
 * 3.  Finish the zip stream:
 *
 *     $zip->finish();
 *
 * You can also add an archive comment, add comments to individual files,
 * and adjust the timestamp of files. See the API documentation for each
 * method below for additional information.
 *
 * Example:
 *
 *   // create a new zip stream object
 *   $zip = new ZipStream('some_files.zip');
 *
 *   // list of local files
 *   $files = array('foo.txt', 'bar.jpg');
 *
 *   // read and add each file to the archive
 *   foreach ($files as $path)
 *     $zip->addFile($path, file_get_contents($path));
 *
 *   // write archive footer to stream
 *   $zip->finish();
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
     */
    const ZIP_VERSION_MADE_BY = 0x603;

    /**
     * The following signatures end with 0x4b50, which in ASCII is PK,
     * the initials of the inventor Phil Katz.
     * See https://en.wikipedia.org/wiki/Zip_(file_format)#File_headers
     */
    const FILE_HEADER_SIGNATURE = 0x04034b50;
    const CDR_FILE_SIGNATURE = 0x02014b50;
    const CDR_EOF_SIGNATURE = 0x06054b50;
    const DATA_DESCRIPTOR_SIGNATURE = 0x08074b50;
    const ZIP64_CDR_EOF_SIGNATURE = 0x06064b50;
    const ZIP64_CDR_LOCATOR_SIGNATURE = 0x07064b50;

    /**
     * Global Options
     *
     * @var ArchiveOptions
     */
    public $opt;

    /**
     * @var array
     */
    public $files = [];

    /**
     * @var Bigint
     */
    public $cdr_ofs;

    /**
     * @var Bigint
     */
    public $ofs;

    /**
     * @var bool
     */
    protected $need_headers;

    /**
     * @var null|String
     */
    protected $output_name;

    /**
     * Create a new ZipStream object.
     *
     * Parameters:
     *
     * @param String $name - Name of output file (optional).
     * @param ArchiveOptions $opt - Archive Options
     *
     * Large File Support:
     *
     * By default, the method addFileFromPath() will send send files
     * larger than 20 megabytes along raw rather than attempting to
     * compress them.  You can change both the maximum size and the
     * compression behavior using the largeFile* options above, with the
     * following caveats:
     *
     * * For "small" files (e.g. files smaller than largeFileSize), the
     *   memory use can be up to twice that of the actual file.  In other
     *   words, adding a 10 megabyte file to the archive could potentially
     *   occupy 20 megabytes of memory.
     *
     * * Enabling compression on large files (e.g. files larger than
     *   large_file_size) is extremely slow, because ZipStream has to pass
     *   over the large file once to calculate header information, and then
     *   again to compress and send the actual data.
     *
     * Examples:
     *
     *   // create a new zip file named 'foo.zip'
     *   $zip = new ZipStream('foo.zip');
     *
     *   // create a new zip file named 'bar.zip' with a comment
     *   $opt->setComment = 'this is a comment for the zip file.';
     *   $zip = new ZipStream('bar.zip', $opt);
     *
     * Notes:
     *
     * In order to let this library send HTTP headers, a filename must be given
     * _and_ the option `sendHttpHeaders` must be `true`. This behavior is to
     * allow software to send its own headers (including the filename), and
     * still use this library.
     */
    public function __construct(?string $name = null, ?ArchiveOptions $opt = null)
    {
        $this->opt = $opt ?: new ArchiveOptions();

        $this->output_name = $name;
        $this->need_headers = $name && $this->opt->isSendHttpHeaders();

        $this->cdr_ofs = new Bigint();
        $this->ofs = new Bigint();
    }

    /**
     * addFile
     *
     * Add a file to the archive.
     *
     * @param String $name - path of file in archive (including directory).
     * @param String $data - contents of file
     * @param FileOptions $options
     *
     * File Options:
     *  time     - Last-modified timestamp (seconds since the epoch) of
     *             this file.  Defaults to the current time.
     *  comment  - Comment related to this file.
     *  method   - Storage method for file ("store" or "deflate")
     *
     * Examples:
     *
     *   // add a file named 'foo.txt'
     *   $data = file_get_contents('foo.txt');
     *   $zip->addFile('foo.txt', $data);
     *
     *   // add a file named 'bar.jpg' with a comment and a last-modified
     *   // time of two hours ago
     *   $data = file_get_contents('bar.jpg');
     *   $opt->setTime = time() - 2 * 3600;
     *   $opt->setComment = 'this is a comment about bar.jpg';
     *   $zip->addFile('bar.jpg', $data, $opt);
     */
    public function addFile(string $name, string $data, ?FileOptions $options = null): void
    {
        $options = $options ?: new FileOptions();
        $options->defaultTo($this->opt);

        $file = new File($this, $name, $options);
        $file->processData($data);
    }

    /**
     * addFileFromPath
     *
     * Add a file at path to the archive.
     *
     * Note that large files may be compressed differently than smaller
     * files; see the "Large File Support" section above for more
     * information.
     *
     * @param String $name - name of file in archive (including directory path).
     * @param String $path - path to file on disk (note: paths should be encoded using
     *          UNIX-style forward slashes -- e.g '/path/to/some/file').
     * @param FileOptions $options
     *
     * File Options:
     *  time     - Last-modified timestamp (seconds since the epoch) of
     *             this file.  Defaults to the current time.
     *  comment  - Comment related to this file.
     *  method   - Storage method for file ("store" or "deflate")
     *
     * Examples:
     *
     *   // add a file named 'foo.txt' from the local file '/tmp/foo.txt'
     *   $zip->addFileFromPath('foo.txt', '/tmp/foo.txt');
     *
     *   // add a file named 'bigfile.rar' from the local file
     *   // '/usr/share/bigfile.rar' with a comment and a last-modified
     *   // time of two hours ago
     *   $path = '/usr/share/bigfile.rar';
     *   $opt->setTime = time() - 2 * 3600;
     *   $opt->setComment = 'this is a comment about bar.jpg';
     *   $zip->addFileFromPath('bigfile.rar', $path, $opt);
     *
     * @return void
     * @throws \ZipStream\Exception\FileNotFoundException
     * @throws \ZipStream\Exception\FileNotReadableException
     */
    public function addFileFromPath(string $name, string $path, ?FileOptions $options = null): void
    {
        $options = $options ?: new FileOptions();
        $options->defaultTo($this->opt);

        $file = new File($this, $name, $options);
        $file->processPath($path);
    }

    /**
     * addFileFromStream
     *
     * Add an open stream to the archive.
     *
     * @param String $name - path of file in archive (including directory).
     * @param resource $stream - contents of file as a stream resource
     * @param FileOptions $options
     *
     * File Options:
     *  time     - Last-modified timestamp (seconds since the epoch) of
     *             this file.  Defaults to the current time.
     *  comment  - Comment related to this file.
     *
     * Examples:
     *
     *   // create a temporary file stream and write text to it
     *   $fp = tmpfile();
     *   fwrite($fp, 'The quick brown fox jumped over the lazy dog.');
     *
     *   // add a file named 'streamfile.txt' from the content of the stream
     *   $x->addFileFromStream('streamfile.txt', $fp);
     *
     * @return void
     */
    public function addFileFromStream(string $name, $stream, ?FileOptions $options = null): void
    {
        $options = $options ?: new FileOptions();
        $options->defaultTo($this->opt);

        $file = new File($this, $name, $options);
        $file->processStream(new DeflateStream($stream));
    }

    /**
     * addFileFromPsr7Stream
     *
     * Add an open stream to the archive.
     *
     * @param String $name - path of file in archive (including directory).
     * @param StreamInterface $stream - contents of file as a stream resource
     * @param FileOptions $options
     *
     * File Options:
     *  time     - Last-modified timestamp (seconds since the epoch) of
     *             this file.  Defaults to the current time.
     *  comment  - Comment related to this file.
     *
     * Examples:
     *
     *   // create a temporary file stream and write text to it
     *   $fp = tmpfile();
     *   fwrite($fp, 'The quick brown fox jumped over the lazy dog.');
     *
     *   // add a file named 'streamfile.txt' from the content of the stream
     *   $x->addFileFromPsr7Stream('streamfile.txt', $fp);
     *
     * @return void
     */
    public function addFileFromPsr7Stream(
        string $name,
        StreamInterface $stream,
        ?FileOptions $options = null
    ): void {
        $options = $options ?: new FileOptions();
        $options->defaultTo($this->opt);

        $file = new File($this, $name, $options);
        $file->processStream($stream);
    }

    /**
     * finish
     *
     * Write zip footer to stream.
     *
     *  Example:
     *
     *   // add a list of files to the archive
     *   $files = array('foo.txt', 'bar.jpg');
     *   foreach ($files as $path)
     *     $zip->addFile($path, file_get_contents($path));
     *
     *   // write footer to stream
     *   $zip->finish();
     * @return void
     *
     * @throws OverflowException
     */
    public function finish(): void
    {
        // add trailing cdr file records
        foreach ($this->files as $cdrFile) {
            $this->send($cdrFile);
            $this->cdr_ofs = $this->cdr_ofs->add(Bigint::init(strlen($cdrFile)));
        }

        // Add 64bit headers (if applicable)
        if (count($this->files) >= 0xFFFF ||
            $this->cdr_ofs->isOver32() ||
            $this->ofs->isOver32()) {
            if (!$this->opt->isEnableZip64()) {
                throw new OverflowException();
            }

            $this->addCdr64Eof();
            $this->addCdr64Locator();
        }

        // add trailing cdr eof record
        $this->addCdrEof();

        // The End
        $this->clear();
    }

    /**
     * Send ZIP64 CDR EOF (Central Directory Record End-of-File) record.
     *
     * @return void
     */
    protected function addCdr64Eof(): void
    {
        $num_files = count($this->files);
        $cdr_length = $this->cdr_ofs;
        $cdr_offset = $this->ofs;

        $fields = [
            ['V', static::ZIP64_CDR_EOF_SIGNATURE],     // ZIP64 end of central file header signature
            ['P', 44],                                  // Length of data below this header (length of block - 12) = 44
            ['v', static::ZIP_VERSION_MADE_BY],         // Made by version
            ['v', Version::ZIP64],                      // Extract by version
            ['V', 0x00],                                // disk number
            ['V', 0x00],                                // no of disks
            ['P', $num_files],                          // no of entries on disk
            ['P', $num_files],                          // no of entries in cdr
            ['P', $cdr_length],                         // CDR size
            ['P', $cdr_offset],                         // CDR offset
        ];

        $ret = static::packFields($fields);
        $this->send($ret);
    }

    /**
     * Create a format string and argument list for pack(), then call
     * pack() and return the result.
     *
     * @param array $fields
     * @return string
     */
    public static function packFields(array $fields): string
    {
        $fmt = '';
        $args = [];

        // populate format string and argument list
        foreach ($fields as [$format, $value]) {
            if ($format === 'P') {
                $fmt .= 'VV';
                if ($value instanceof Bigint) {
                    $args[] = $value->getLow32();
                    $args[] = $value->getHigh32();
                } else {
                    $args[] = $value;
                    $args[] = 0;
                }
            } else {
                if ($value instanceof Bigint) {
                    $value = $value->getLow32();
                }
                $fmt .= $format;
                $args[] = $value;
            }
        }

        // prepend format string to argument list
        array_unshift($args, $fmt);

        // build output string from header and compressed data
        return pack(...$args);
    }

    /**
     * Send string, sending HTTP headers if necessary.
     * Flush output after write if configure option is set.
     *
     * @param String $str
     * @return void
     */
    public function send(string $str): void
    {
        if ($this->need_headers) {
            $this->sendHttpHeaders();
        }
        $this->need_headers = false;

        fwrite($this->opt->getOutputStream(), $str);

        if ($this->opt->isFlushOutput()) {
            // flush output buffer if it is on and flushable
            $status = ob_get_status();
            if (isset($status['flags']) && ($status['flags'] & PHP_OUTPUT_HANDLER_FLUSHABLE)) {
                ob_flush();
            }

            // Flush system buffers after flushing userspace output buffer
            flush();
        }
    }

    /**
     * Send HTTP headers for this stream.
     *
     * @return void
     */
    protected function sendHttpHeaders(): void
    {
        // grab content disposition
        $disposition = $this->opt->getContentDisposition();

        if ($this->output_name) {
            // Various different browsers dislike various characters here. Strip them all for safety.
            $safe_output = trim(str_replace(['"', "'", '\\', ';', "\n", "\r"], '', $this->output_name));

            // Check if we need to UTF-8 encode the filename
            $urlencoded = rawurlencode($safe_output);
            $disposition .= "; filename*=UTF-8''{$urlencoded}";
        }

        $headers = array(
            'Content-Type' => $this->opt->getContentType(),
            'Content-Disposition' => $disposition,
            'Pragma' => 'public',
            'Cache-Control' => 'public, must-revalidate',
            'Content-Transfer-Encoding' => 'binary'
        );

        $call = $this->opt->getHttpHeaderCallback();
        foreach ($headers as $key => $val) {
            $call("$key: $val");
        }
    }

    /**
     * Send ZIP64 CDR Locator (Central Directory Record Locator) record.
     *
     * @return void
     */
    protected function addCdr64Locator(): void
    {
        $cdr_offset = $this->ofs->add($this->cdr_ofs);

        $fields = [
            ['V', static::ZIP64_CDR_LOCATOR_SIGNATURE], // ZIP64 end of central file header signature
            ['V', 0x00],                                // Disc number containing CDR64EOF
            ['P', $cdr_offset],                         // CDR offset
            ['V', 1],                                   // Total number of disks
        ];

        $ret = static::packFields($fields);
        $this->send($ret);
    }

    /**
     * Send CDR EOF (Central Directory Record End-of-File) record.
     *
     * @return void
     */
    protected function addCdrEof(): void
    {
        $num_files = count($this->files);
        $cdr_length = $this->cdr_ofs;
        $cdr_offset = $this->ofs;

        // grab comment (if specified)
        $comment = $this->opt->getComment();

        $fields = [
            ['V', static::CDR_EOF_SIGNATURE],   // end of central file header signature
            ['v', 0x00],                        // disk number
            ['v', 0x00],                        // no of disks
            ['v', min($num_files, 0xFFFF)],     // no of entries on disk
            ['v', min($num_files, 0xFFFF)],     // no of entries in cdr
            ['V', $cdr_length->getLowFF()],     // CDR size
            ['V', $cdr_offset->getLowFF()],     // CDR offset
            ['v', strlen($comment)],            // Zip Comment size
        ];

        $ret = static::packFields($fields) . $comment;
        $this->send($ret);
    }

    /**
     * Clear all internal variables. Note that the stream object is not
     * usable after this.
     *
     * @return void
     */
    protected function clear(): void
    {
        $this->files = [];
        $this->ofs = new Bigint();
        $this->cdr_ofs = new Bigint();
        $this->opt = new ArchiveOptions();
    }

    /**
     * Is this file larger than large_file_size?
     *
     * @param string $path
     * @return bool
     */
    public function isLargeFile(string $path): bool
    {
        if (!$this->opt->isStatFiles()) {
            return false;
        }
        $stat = stat($path);
        return $stat['size'] > $this->opt->getLargeFileSize();
    }

    /**
     * Save file attributes for trailing CDR record.
     *
     * @param File $file
     * @return void
     */
    public function addToCdr(File $file): void
    {
        $file->ofs = $this->ofs;
        $this->ofs = $this->ofs->add($file->getTotalLength());
        $this->files[] = $file->getCdrFile();
    }
}
