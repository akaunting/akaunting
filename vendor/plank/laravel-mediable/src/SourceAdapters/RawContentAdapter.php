<?php
declare(strict_types=1);

namespace Plank\Mediable\SourceAdapters;

use Plank\Mediable\Helpers\File;

/**
 * Raw content Adapter.
 *
 * Adapts a string representing raw contents.
 */
class RawContentAdapter implements SourceAdapterInterface
{
    /**
     * The source object.
     * @var string
     */
    protected $source;

    /**
     * Constructor.
     * @param string $source
     */
    public function __construct(string $source)
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
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function filename(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function extension(): string
    {
        return (string)File::guessExtension($this->mimeType());
    }

    /**
     * {@inheritdoc}
     */
    public function mimeType(): string
    {
        $fileInfo = new \finfo(FILEINFO_MIME_TYPE);

        return (string)$fileInfo->buffer($this->source);
    }

    /**
     * {@inheritdoc}
     */
    public function contents(): string
    {
        return $this->source;
    }

    /**
     * @inheritdoc
     */
    public function getStreamResource()
    {
        $stream = fopen('php://memory', 'r+b');
        fwrite($stream, $this->contents());
        return $stream;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function size(): int
    {
        return (int)mb_strlen($this->source, '8bit');
    }
}
