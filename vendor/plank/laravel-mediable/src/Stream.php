<?php
declare(strict_types=1);

namespace Plank\Mediable;

use Psr\Http\Message\StreamInterface;

/**
 * PHP stream implementation.
 *
 * @codeCoverageIgnore
 */
class Stream implements StreamInterface
{
    private $resource;
    private $size;
    private $seekable;
    private $readable;
    private $writable;
    private $uri;

    /** @var array Hash of readable and writable stream types */
    private static $readWriteHash = [
        'read' => [
            'r' => true,
            'w+' => true,
            'r+' => true,
            'x+' => true,
            'c+' => true,
            'rb' => true,
            'w+b' => true,
            'r+b' => true,
            'x+b' => true,
            'c+b' => true,
            'rt' => true,
            'w+t' => true,
            'r+t' => true,
            'x+t' => true,
            'c+t' => true,
            'a+' => true
        ],
        'write' => [
            'w' => true,
            'w+' => true,
            'rw' => true,
            'r+' => true,
            'x+' => true,
            'c+' => true,
            'wb' => true,
            'w+b' => true,
            'r+b' => true,
            'x+b' => true,
            'c+b' => true,
            'w+t' => true,
            'r+t' => true,
            'x+t' => true,
            'c+t' => true,
            'a' => true,
            'a+' => true
        ]
    ];

    /**
     * @param resource $resource Stream resource to wrap.
     *
     * @throws \InvalidArgumentException if the stream is not a stream resource
     */
    public function __construct($resource)
    {
        if (!is_resource($resource)) {
            throw new \InvalidArgumentException('Stream must be a resource');
        }

        $this->resource = $resource;
        $metadata = $this->getMetadata();
        $this->seekable = $metadata['seekable'];
        $this->readable = isset(self::$readWriteHash['read'][$metadata['mode']]);
        $this->writable = isset(self::$readWriteHash['write'][$metadata['mode']]);
        $this->uri = $this->getMetadata('uri');
    }

    /**
     * Closes the stream when the destructed
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        try {
            $this->seek(0);
            return (string)stream_get_contents($this->resource);
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContents(): string
    {
        $contents = stream_get_contents($this->resource);

        if ($contents === false) {
            throw new \RuntimeException('Unable to read stream contents');
        }

        return $contents;
    }

    /**
     * {@inheritdoc}
     */
    public function close(): void
    {
        if (!$this->resource) {
            return;
        }

        if (is_resource($this->resource)) {
            fclose($this->resource);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function detach()
    {
        if (!$this->resource) {
            return null;
        }

        $resource = $this->resource;
        unset($this->resource);
        $this->size = $this->uri = null;
        $this->readable = $this->writable = $this->seekable = false;

        return $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize(): ?int
    {
        if ($this->size !== null) {
            return $this->size;
        }

        if (!$this->resource) {
            return null;
        }

        // Clear the stat cache if the stream has a URI
        if ($this->uri) {
            clearstatcache(true, $this->uri);
        }

        $stats = fstat($this->resource);

        if (isset($stats['size'])) {
            $this->size = $stats['size'];
            return $this->size;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable(): bool
    {
        return $this->readable;
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable(): bool
    {
        return $this->writable;
    }

    /**
     * {@inheritdoc}
     */
    public function isSeekable(): bool
    {
        return $this->seekable;
    }

    /**
     * {@inheritdoc}
     */
    public function eof(): bool
    {
        return !$this->resource || feof($this->resource);
    }

    /**
     * {@inheritdoc}
     */
    public function tell(): int
    {
        if (!$this->resource) {
            throw new \RuntimeException('No resource available; cannot tell position');
        }

        $result = ftell($this->resource);

        if ($result === false) {
            throw new \RuntimeException('Unable to determine stream position');
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->seek(0);
    }

    /**
     * {@inheritdoc}
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!$this->resource) {
            throw new \RuntimeException('No resource available; cannot seek position');
        }

        if (!$this->isSeekable()) {
            throw new \RuntimeException('Stream is not seekable');
        }

        $result = fseek($this->resource, $offset, $whence);

        if ($result === -1) {
            throw new \RuntimeException('Unable to seek to stream position '
                . $offset . ' with whence ' . var_export($whence, true));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function read(int $length): string
    {
        if (!$this->resource) {
            throw new \RuntimeException('No resource available; cannot read');
        }

        if (!$this->isReadable()) {
            throw new \RuntimeException('Cannot read from non-readable stream');
        }

        $result = fread($this->resource, $length);

        if ($result === false) {
            throw new \RuntimeException('Unable to read from stream');
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $string): int
    {
        if (!$this->resource) {
            throw new \RuntimeException('No resource available; cannot write');
        }

        if (!$this->isWritable()) {
            throw new \RuntimeException('Cannot write to a non-writable stream');
        }

        // We can't know the size after writing anything
        $this->size = null;
        $result = fwrite($this->resource, $string);

        if ($result === false) {
            throw new \RuntimeException('Unable to write to stream');
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata(?string $key = null)
    {
        $metadata = stream_get_meta_data($this->resource);

        if (is_null($key)) {
            return $metadata;
        }

        return isset($metadata[$key]) ? $metadata[$key] : null;
    }
}
