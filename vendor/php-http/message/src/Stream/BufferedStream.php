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

    /** @var int size of the stream if available */
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

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        try {
            $this->rewind();

            return $this->getContents();
        } catch (\Throwable $throwable) {
            return '';
        } catch (\Exception $exception) { // Layer to be BC with PHP 5, remove this when we only support PHP 7+
            return '';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        if (null === $this->resource) {
            throw new \RuntimeException('Cannot close on a detached stream');
        }

        $this->stream->close();
        fclose($this->resource);
    }

    /**
     * {@inheritdoc}
     */
    public function detach()
    {
        if (null === $this->resource) {
            return;
        }

        // Force reading the remaining data of the stream
        $this->getContents();

        $resource = $this->resource;
        $this->stream->close();
        $this->stream = null;
        $this->resource = null;

        return $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        if (null === $this->resource) {
            return;
        }

        if (null === $this->size && $this->stream->eof()) {
            return $this->written;
        }

        return $this->size;
    }

    /**
     * {@inheritdoc}
     */
    public function tell()
    {
        if (null === $this->resource) {
            throw new \RuntimeException('Cannot tell on a detached stream');
        }

        return ftell($this->resource);
    }

    /**
     * {@inheritdoc}
     */
    public function eof()
    {
        if (null === $this->resource) {
            throw new \RuntimeException('Cannot call eof on a detached stream');
        }

        // We are at the end only when both our resource and underlying stream are at eof
        return $this->stream->eof() && (ftell($this->resource) === $this->written);
    }

    /**
     * {@inheritdoc}
     */
    public function isSeekable()
    {
        return null !== $this->resource;
    }

    /**
     * {@inheritdoc}
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (null === $this->resource) {
            throw new \RuntimeException('Cannot seek on a detached stream');
        }

        fseek($this->resource, $offset, $whence);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        if (null === $this->resource) {
            throw new \RuntimeException('Cannot rewind on a detached stream');
        }

        rewind($this->resource);
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function write($string)
    {
        throw new \RuntimeException('Cannot write on this stream');
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable()
    {
        return null !== $this->resource;
    }

    /**
     * {@inheritdoc}
     */
    public function read($length)
    {
        if (null === $this->resource) {
            throw new \RuntimeException('Cannot read on a detached stream');
        }

        $read = '';

        // First read from the resource
        if (ftell($this->resource) !== $this->written) {
            $read = fread($this->resource, $length);
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

    /**
     * {@inheritdoc}
     */
    public function getContents()
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

    /**
     * {@inheritdoc}
     */
    public function getMetadata($key = null)
    {
        if (null === $this->resource) {
            if (null === $key) {
                return [];
            }

            return;
        }

        $metadata = stream_get_meta_data($this->resource);

        if (null === $key) {
            return $metadata;
        }

        if (!array_key_exists($key, $metadata)) {
            return;
        }

        return $metadata[$key];
    }
}
