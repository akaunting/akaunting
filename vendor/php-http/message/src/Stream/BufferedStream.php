<?php

namespace Http\Message\Stream;

use Psr\Http\Message\StreamInterface;

/**
 * Decorator to make any stream seekable.
 *
 * Internally it buffers an existing StreamInterface into a php://temp resource (or memory). By default it will use
 * 2 megabytes of memory before writing to a temporary disk file.
 *
 * Due to this, very large stream can suffer performance issue (i/o slowdown).
 */
class BufferedStream implements StreamInterface
{
    /** @var resource The buffered resource used to seek previous data */
    private $resource;

    /** @var int|null size of the stream if available */
    private $size;

    /** @var StreamInterface The underlying stream decorated by this class */
    private $stream;

    /** @var int How many bytes were written */
    private $written = 0;

    /**
     * @param StreamInterface $stream        Decorated stream
     * @param bool            $useFileBuffer Whether to use a file buffer (write to a file, if data exceed a certain size)
     *                                       by default, set this to false to only use memory
     * @param int             $memoryBuffer  In conjunction with using file buffer, limit (in bytes) from which it begins to buffer
     *                                       the data in a file
     */
    public function __construct(StreamInterface $stream, $useFileBuffer = true, $memoryBuffer = 2097152)
    {
        $this->stream = $stream;
        $this->size = $stream->getSize();

        if ($useFileBuffer) {
            $this->resource = fopen('php://temp/maxmemory:'.$memoryBuffer, 'rw+');
        } else {
            $this->resource = fopen('php://memory', 'rw+');
        }

        if (false === $this->resource) {
            throw new \RuntimeException('Cannot create a resource over temp or memory implementation');
        }
    }

    public function __toString(): string
    {
        try {
            $this->rewind();

            return $this->getContents();
        } catch (\Throwable $throwable) {
            return '';
        }
    }

    public function close(): void
    {
        if (null === $this->resource) {
            throw new \RuntimeException('Cannot close on a detached stream');
        }

        $this->stream->close();
        fclose($this->resource);
    }

    public function detach()
    {
        if (null === $this->resource) {
            return null;
        }

        // Force reading the remaining data of the stream
        $this->getContents();

        $resource = $this->resource;
        $this->stream->close();
        $this->stream = null;
        $this->resource = null;

        return $resource;
    }

    public function getSize(): ?int
    {
        if (null === $this->resource) {
            return null;
        }

        if (null === $this->size && $this->stream->eof()) {
            return $this->written;
        }

        return $this->size;
    }

    public function tell(): int
    {
        if (null === $this->resource) {
            throw new \RuntimeException('Cannot tell on a detached stream');
        }

        $tell = ftell($this->resource);
        if (false === $tell) {
            throw new \RuntimeException('ftell failed');
        }

        return $tell;
    }

    public function eof(): bool
    {
        if (null === $this->resource) {
            throw new \RuntimeException('Cannot call eof on a detached stream');
        }

        // We are at the end only when both our resource and underlying stream are at eof
        return $this->stream->eof() && (ftell($this->resource) === $this->written);
    }

    public function isSeekable(): bool
    {
        return null !== $this->resource;
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (null === $this->resource) {
            throw new \RuntimeException('Cannot seek on a detached stream');
        }

        fseek($this->resource, $offset, $whence);
    }

    public function rewind(): void
    {
        if (null === $this->resource) {
            throw new \RuntimeException('Cannot rewind on a detached stream');
        }

        rewind($this->resource);
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function write(string $string): int
    {
        throw new \RuntimeException('Cannot write on this stream');
    }

    public function isReadable(): bool
    {
        return null !== $this->resource;
    }

    public function read(int $length): string
    {
        if (null === $this->resource) {
            throw new \RuntimeException('Cannot read on a detached stream');
        }
        if ($length < 0) {
            throw new \InvalidArgumentException('Can not read a negative amount of bytes');
        }

        $read = '';

        // First read from the resource
        if (ftell($this->resource) !== $this->written) {
            $read = fread($this->resource, $length);
        }
        if (false === $read) {
            throw new \RuntimeException('Failed to read from resource');
        }

        $bytesRead = strlen($read);

        if ($bytesRead < $length) {
            $streamRead = $this->stream->read($length - $bytesRead);

            // Write on the underlying stream what we read
            $this->written += fwrite($this->resource, $streamRead);
            $read .= $streamRead;
        }

        return $read;
    }

    public function getContents(): string
    {
        if (null === $this->resource) {
            throw new \RuntimeException('Cannot read on a detached stream');
        }

        $read = '';

        while (!$this->eof()) {
            $read .= $this->read(8192);
        }

        return $read;
    }

    public function getMetadata(?string $key = null)
    {
        if (null === $this->resource) {
            if (null === $key) {
                return [];
            }

            return null;
        }

        $metadata = stream_get_meta_data($this->resource);

        if (null === $key) {
            return $metadata;
        }

        if (!array_key_exists($key, $metadata)) {
            return null;
        }

        return $metadata[$key];
    }
}
