<?php
declare(strict_types=1);

namespace Plank\Mediable\SourceAdapters;

use Plank\Mediable\Helpers\File;
use Psr\Http\Message\StreamInterface;

/**
 * Stream Adapter.
 *
 * Adapts a stream object or resource.
 */
class StreamAdapter implements SourceAdapterInterface
{
    const BUFFER_SIZE = 1024;

    /**
     * The source object.
     * @var StreamInterface
     */
    protected $source;

    /**
     * The contents of the stream.
     * @var string|null
     */
    protected $contents;

    /**
     * Constructor.
     * @param StreamInterface $source
     */
    public function __construct(StreamInterface $source)
    {
        $this->source = $source;
    }

    /**
     * {@inheritdoc}
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function path(): string
    {
        return (string)$this->source->getMetadata('uri');
    }

    /**
     * {@inheritdoc}
     */
    public function filename(): string
    {
        return pathinfo(parse_url($this->path(), PHP_URL_PATH) ?? '', PATHINFO_FILENAME);
    }

    /**
     * {@inheritdoc}
     */
    public function extension(): string
    {
        $extension = pathinfo(
            parse_url($this->path(), PHP_URL_PATH) ?? '',
            PATHINFO_EXTENSION
        );

        if ($extension) {
            return $extension;
        }

        return (string)File::guessExtension($this->mimeType());
    }

    /**
     * {@inheritdoc}
     */
    public function mimeType(): string
    {
        $fileInfo = new \finfo(FILEINFO_MIME_TYPE);

        return (string)$fileInfo->buffer($this->contents());
    }

    /**
     * {@inheritdoc}
     */
    public function contents(): string
    {
        if (is_null($this->contents)) {
            if ($this->source->isSeekable()) {
                $this->contents = (string)$this->source;
            } else {
                $this->contents = $this->source->getContents();
            }
        }

        return $this->contents;
    }

    /**
     * @inheritdoc
     */
    public function getStreamResource()
    {
        if ($this->source->isSeekable()) {
            $this->source->rewind();
        }

        $stream = fopen('php://temp', 'r+b');

        while (!$this->source->eof()) {
            $writeResult = fwrite($stream, $this->source->read(self::BUFFER_SIZE));
            if ($writeResult === false) {
                throw new \RuntimeException("Could not read Stream");
            }
        }

        if ($this->source->isSeekable()) {
            $this->source->rewind();
        }

        return $stream;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return $this->source->isReadable();
    }

    /**
     * {@inheritdoc}
     */
    public function size(): int
    {
        $size = $this->source->getSize();

        if (!is_null($size)) {
            return $size;
        }

        return (int)mb_strlen($this->contents(), '8bit');
    }
}
