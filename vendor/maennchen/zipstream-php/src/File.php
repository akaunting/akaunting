<?php
declare(strict_types=1);

namespace ZipStream;

use Psr\Http\Message\StreamInterface;
use ZipStream\Exception\EncodingException;
use ZipStream\Exception\FileNotFoundException;
use ZipStream\Exception\FileNotReadableException;
use ZipStream\Exception\OverflowException;
use ZipStream\Option\File as FileOptions;
use ZipStream\Option\Method;
use ZipStream\Option\Version;

class File
{
    const HASH_ALGORITHM = 'crc32b';

    const BIT_ZERO_HEADER = 0x0008;
    const BIT_EFS_UTF8 = 0x0800;

    const COMPUTE = 1;
    const SEND = 2;

    private const CHUNKED_READ_BLOCK_SIZE = 1048576;

    /**
     * @var string
     */
    public $name;

    /**
     * @var FileOptions
     */
    public $opt;

    /**
     * @var Bigint
     */
    public $len;
    /**
     * @var Bigint
     */
    public $zlen;

    /** @var  int */
    public $crc;

    /**
     * @var Bigint
     */
    public $hlen;

    /**
     * @var Bigint
     */
    public $ofs;

    /**
     * @var int
     */
    public $bits;

    /**
     * @var Version
     */
    public $version;

    /**
     * @var ZipStream
     */
    public $zip;

    /**
     * @var resource
     */
    private $deflate;
    /**
     * @var resource
     */
    private $hash;

    /**
     * @var Method
     */
    private $method;

    /**
     * @var Bigint
     */
    private $totalLength;

    public function __construct(ZipStream $zip, string $name, ?FileOptions $opt = null)
    {
        $this->zip = $zip;

        $this->name = $name;
        $this->opt = $opt ?: new FileOptions();
        $this->method = $this->opt->getMethod();
        $this->version = Version::STORE();
        $this->ofs = new Bigint();
    }

    public function processPath(string $path): void
    {
        if (!is_readable($path)) {
            if (!file_exists($path)) {
                throw new FileNotFoundException($path);
            }
            throw new FileNotReadableException($path);
        }
        if ($this->zip->isLargeFile($path) === false) {
            $data = file_get_contents($path);
            $this->processData($data);
        } else {
            $this->method = $this->zip->opt->getLargeFileMethod();

            $stream = new DeflateStream(fopen($path, 'rb'));
            $this->processStream($stream);
            $stream->close();
        }
    }

    public function processData(string $data): void
    {
        $this->len = new Bigint(strlen($data));
        $this->crc = crc32($data);

        // compress data if needed
        if ($this->method->equals(Method::DEFLATE())) {
            $data = gzdeflate($data);
        }

        $this->zlen = new Bigint(strlen($data));
        $this->addFileHeader();
        $this->zip->send($data);
        $this->addFileFooter();
    }

    /**
     * Create and send zip header for this file.
     *
     * @return void
     * @throws \ZipStream\Exception\EncodingException
     */
    public function addFileHeader(): void
    {
        $name = static::filterFilename($this->name);

        // calculate name length
        $nameLength = strlen($name);

        // create dos timestamp
        $time = static::dosTime($this->opt->getTime()->getTimestamp());

        $comment = $this->opt->getComment();

        if (!mb_check_encoding($name, 'ASCII') ||
            !mb_check_encoding($comment, 'ASCII')) {
            // Sets Bit 11: Language encoding flag (EFS).  If this bit is set,
            // the filename and comment fields for this file
            // MUST be encoded using UTF-8. (see APPENDIX D)
            if (!mb_check_encoding($name, 'UTF-8') ||
                !mb_check_encoding($comment, 'UTF-8')) {
                throw new EncodingException(
                    'File name and comment should use UTF-8 ' .
                    'if one of them does not fit into ASCII range.'
                );
            }
            $this->bits |= self::BIT_EFS_UTF8;
        }

        if ($this->method->equals(Method::DEFLATE())) {
            $this->version = Version::DEFLATE();
        }

        $force = (boolean)($this->bits & self::BIT_ZERO_HEADER) &&
            $this->zip->opt->isEnableZip64();

        $footer = $this->buildZip64ExtraBlock($force);

        // If this file will start over 4GB limit in ZIP file,
        // CDR record will have to use Zip64 extension to describe offset
        // to keep consistency we use the same value here
        if ($this->zip->ofs->isOver32()) {
            $this->version = Version::ZIP64();
        }

        $fields = [
            ['V', ZipStream::FILE_HEADER_SIGNATURE],
            ['v', $this->version->getValue()],      // Version needed to Extract
            ['v', $this->bits],                     // General purpose bit flags - data descriptor flag set
            ['v', $this->method->getValue()],       // Compression method
            ['V', $time],                           // Timestamp (DOS Format)
            ['V', $this->crc],                      // CRC32 of data (0 -> moved to data descriptor footer)
            ['V', $this->zlen->getLowFF($force)],   // Length of compressed data (forced to 0xFFFFFFFF for zero header)
            ['V', $this->len->getLowFF($force)],    // Length of original data (forced to 0xFFFFFFFF for zero header)
            ['v', $nameLength],                     // Length of filename
            ['v', strlen($footer)],                 // Extra data (see above)
        ];

        // pack fields and calculate "total" length
        $header = ZipStream::packFields($fields);

        // print header and filename
        $data = $header . $name . $footer;
        $this->zip->send($data);

        // save header length
        $this->hlen = Bigint::init(strlen($data));
    }

    /**
     * Strip characters that are not legal in Windows filenames
     * to prevent compatibility issues
     *
     * @param string $filename Unprocessed filename
     * @return string
     */
    public static function filterFilename(string $filename): string
    {
        // strip leading slashes from file name
        // (fixes bug in windows archive viewer)
        $filename = preg_replace('/^\\/+/', '', $filename);

        return str_replace(['\\', ':', '*', '?', '"', '<', '>', '|'], '_', $filename);
    }

    /**
     * Convert a UNIX timestamp to a DOS timestamp.
     *
     * @param int $when
     * @return int DOS Timestamp
     */
    final protected static function dosTime(int $when): int
    {
        // get date array for timestamp
        $d = getdate($when);

        // set lower-bound on dates
        if ($d['year'] < 1980) {
            $d = array(
                'year' => 1980,
                'mon' => 1,
                'mday' => 1,
                'hours' => 0,
                'minutes' => 0,
                'seconds' => 0
            );
        }

        // remove extra years from 1980
        $d['year'] -= 1980;

        // return date string
        return
            ($d['year'] << 25) |
            ($d['mon'] << 21) |
            ($d['mday'] << 16) |
            ($d['hours'] << 11) |
            ($d['minutes'] << 5) |
            ($d['seconds'] >> 1);
    }

    protected function buildZip64ExtraBlock(bool $force = false): string
    {

        $fields = [];
        if ($this->len->isOver32($force)) {
            $fields[] = ['P', $this->len];          // Length of original data
        }

        if ($this->len->isOver32($force)) {
            $fields[] = ['P', $this->zlen];         // Length of compressed data
        }

        if ($this->ofs->isOver32()) {
            $fields[] = ['P', $this->ofs];          // Offset of local header record
        }

        if (!empty($fields)) {
            if (!$this->zip->opt->isEnableZip64()) {
                throw new OverflowException();
            }

            array_unshift(
                $fields,
                ['v', 0x0001],                      // 64 bit extension
                ['v', count($fields) * 8]             // Length of data block
            );
            $this->version = Version::ZIP64();
        }

        return ZipStream::packFields($fields);
    }

    /**
     * Create and send data descriptor footer for this file.
     *
     * @return void
     */

    public function addFileFooter(): void
    {

        if ($this->bits & self::BIT_ZERO_HEADER) {
            // compressed and uncompressed size
            $sizeFormat = 'V';
            if ($this->zip->opt->isEnableZip64()) {
                $sizeFormat = 'P';
            }
            $fields = [
                ['V', ZipStream::DATA_DESCRIPTOR_SIGNATURE],
                ['V', $this->crc],              // CRC32
                [$sizeFormat, $this->zlen],     // Length of compressed data
                [$sizeFormat, $this->len],      // Length of original data
            ];

            $footer = ZipStream::packFields($fields);
            $this->zip->send($footer);
        } else {
            $footer = '';
        }
        $this->totalLength = $this->hlen->add($this->zlen)->add(Bigint::init(strlen($footer)));
        $this->zip->addToCdr($this);
    }

    public function processStream(StreamInterface $stream): void
    {
        $this->zlen = new Bigint();
        $this->len = new Bigint();

        if ($this->zip->opt->isZeroHeader()) {
            $this->processStreamWithZeroHeader($stream);
        } else {
            $this->processStreamWithComputedHeader($stream);
        }
    }

    protected function processStreamWithZeroHeader(StreamInterface $stream): void
    {
        $this->bits |= self::BIT_ZERO_HEADER;
        $this->addFileHeader();
        $this->readStream($stream, self::COMPUTE | self::SEND);
        $this->addFileFooter();
    }

    protected function readStream(StreamInterface $stream, ?int $options = null): void
    {
        $this->deflateInit();
        $total = 0;
        $size = $this->opt->getSize();
        while (!$stream->eof() && ($size === 0 || $total < $size)) {
            $data = $stream->read(self::CHUNKED_READ_BLOCK_SIZE);
            $total += strlen($data);
            if ($size > 0 && $total > $size) {
                $data = substr($data, 0 , strlen($data)-($total - $size));
            }
            $this->deflateData($stream, $data, $options);
            if ($options & self::SEND) {
                $this->zip->send($data);
            }
        }
        $this->deflateFinish($options);
    }

    protected function deflateInit(): void
    {
        $this->hash = hash_init(self::HASH_ALGORITHM);
        if ($this->method->equals(Method::DEFLATE())) {
            $this->deflate = deflate_init(
                ZLIB_ENCODING_RAW,
                ['level' => $this->opt->getDeflateLevel()]
            );
        }
    }

    protected function deflateData(StreamInterface $stream, string &$data, ?int $options = null): void
    {
        if ($options & self::COMPUTE) {
            $this->len = $this->len->add(Bigint::init(strlen($data)));
            hash_update($this->hash, $data);
        }
        if ($this->deflate) {
            $data = deflate_add(
                $this->deflate,
                $data,
                $stream->eof()
                    ? ZLIB_FINISH
                    : ZLIB_NO_FLUSH
            );
        }
        if ($options & self::COMPUTE) {
            $this->zlen = $this->zlen->add(Bigint::init(strlen($data)));
        }
    }

    protected function deflateFinish(?int $options = null): void
    {
        if ($options & self::COMPUTE) {
            $this->crc = hexdec(hash_final($this->hash));
        }
    }

    protected function processStreamWithComputedHeader(StreamInterface $stream): void
    {
        $this->readStream($stream, self::COMPUTE);
        $stream->rewind();

        // incremental compression with deflate_add
        // makes this second read unnecessary
        // but it is only available from PHP 7.0
        if (!$this->deflate && $stream instanceof DeflateStream && $this->method->equals(Method::DEFLATE())) {
            $stream->addDeflateFilter($this->opt);
            $this->zlen = new Bigint();
            while (!$stream->eof()) {
                $data = $stream->read(self::CHUNKED_READ_BLOCK_SIZE);
                $this->zlen = $this->zlen->add(Bigint::init(strlen($data)));
            }
            $stream->rewind();
        }

        $this->addFileHeader();
        $this->readStream($stream, self::SEND);
        $this->addFileFooter();
    }

    /**
     * Send CDR record for specified file.
     *
     * @return string
     */
    public function getCdrFile(): string
    {
        $name = static::filterFilename($this->name);

        // get attributes
        $comment = $this->opt->getComment();

        // get dos timestamp
        $time = static::dosTime($this->opt->getTime()->getTimestamp());

        $footer = $this->buildZip64ExtraBlock();

        $fields = [
            ['V', ZipStream::CDR_FILE_SIGNATURE],   // Central file header signature
            ['v', ZipStream::ZIP_VERSION_MADE_BY],  // Made by version
            ['v', $this->version->getValue()],      // Extract by version
            ['v', $this->bits],                     // General purpose bit flags - data descriptor flag set
            ['v', $this->method->getValue()],       // Compression method
            ['V', $time],                           // Timestamp (DOS Format)
            ['V', $this->crc],                      // CRC32
            ['V', $this->zlen->getLowFF()],         // Compressed Data Length
            ['V', $this->len->getLowFF()],          // Original Data Length
            ['v', strlen($name)],                   // Length of filename
            ['v', strlen($footer)],                 // Extra data len (see above)
            ['v', strlen($comment)],                // Length of comment
            ['v', 0],                               // Disk number
            ['v', 0],                               // Internal File Attributes
            ['V', 32],                              // External File Attributes
            ['V', $this->ofs->getLowFF()]           // Relative offset of local header
        ];

        // pack fields, then append name and comment
        $header = ZipStream::packFields($fields);

        return $header . $name . $footer . $comment;
    }

    /**
     * @return Bigint
     */
    public function getTotalLength(): Bigint
    {
        return $this->totalLength;
    }
}
