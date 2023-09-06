<?php

declare(strict_types=1);

namespace ZipStream\Test;

use Psr\Http\Message\StreamInterface;
use RuntimeException;

/**
 * @internal
 */
class ResourceStream implements StreamInterface
{
    public function __construct(
        /**
         * @var resource
         */
        private $stream
    ) {
    }

    public function __toString(): string
    {
        if ($this->isSeekable()) {
            $this->seek(0);
        }
        return (string) stream_get_contents($this->stream);
    }

    public function close(): void
    {
        $stream = $this->detach();
        if ($stream) {
            fclose($stream);
        }
    }

    public function detach()
    {
        $result = $this->stream;
        // According to the interface, the stream is left in an unusable state;
        /** @psalm-suppress PossiblyNullPropertyAssignmentValue */
        $this->stream = null;
        return $result;
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!$this->isSeekable()) {
            throw new RuntimeException();
        }
        if (fseek($this->stream, $offset, $whence) !== 0) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException();
            // @codeCoverageIgnoreEnd
        }
    }

    public function isSeekable(): bool
    {
        return (bool)$this->getMetadata('seekable');
    }

    public function getMetadata(?string $key = null)
    {
        $metadata = stream_get_meta_data($this->stream);
        return $key !== null ? @$metadata[$key] : $metadata;
    }

    public function getSize(): ?int
    {
        $stats = fstat($this->stream);
        return $stats['size'];
    }

    public function tell(): int
    {
        $position = ftell($this->stream);
        if ($position === false) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException();
            // @codeCoverageIgnoreEnd
        }
        return $position;
    }

    public function eof(): bool
    {
        return feof($this->stream);
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function write(string $string): int
    {
        if (!$this->isWritable()) {
            throw new RuntimeException();
        }
        if (fwrite($this->stream, $string) === false) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException();
            // @codeCoverageIgnoreEnd
        }
        return strlen($string);
    }

    public function isWritable(): bool
    {
        $mode = $this->getMetadata('mode');
        if (!is_string($mode)) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Could not get stream mode from metadata!');
            // @codeCoverageIgnoreEnd
        }
        return preg_match('/[waxc+]/', $mode) === 1;
    }

    public function read(int $length): string
    {
        if (!$this->isReadable()) {
            throw new RuntimeException();
        }
        $result = fread($this->stream, $length);
        if ($result === false) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException();
            // @codeCoverageIgnoreEnd
        }
        return $result;
    }

    public function isReadable(): bool
    {
        $mode = $this->getMetadata('mode');
        if (!is_string($mode)) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Could not get stream mode from metadata!');
            // @codeCoverageIgnoreEnd
        }
        return preg_match('/[r+]/', $mode) === 1;
    }

    public function getContents(): string
    {
        if (!$this->isReadable()) {
            throw new RuntimeException();
        }
        $result = stream_get_contents($this->stream);
        if ($result === false) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException();
            // @codeCoverageIgnoreEnd
        }
        return $result;
    }
}
