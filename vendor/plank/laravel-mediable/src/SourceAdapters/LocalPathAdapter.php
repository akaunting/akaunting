<?php
declare(strict_types=1);

namespace Plank\Mediable\SourceAdapters;

use Plank\Mediable\Helpers\File;

/**
 * Local Path Adapter.
 *
 * Adapts a string representing an absolute path
 */
class LocalPathAdapter implements SourceAdapterInterface
{
    /**
     * The source string.
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

    public function getSource()
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function path(): string
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function filename(): string
    {
        return pathinfo($this->source, PATHINFO_FILENAME);
    }

    /**
     * {@inheritdoc}
     */
    public function extension(): string
    {
        $extension = pathinfo($this->source, PATHINFO_EXTENSION);

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
        return mime_content_type($this->source);
    }

    /**
     * {@inheritdoc}
     */
    public function contents(): string
    {
        return (string)file_get_contents($this->source);
    }

    /**
     * @inheritdoc
     */
    public function getStreamResource()
    {
        return fopen($this->path(), 'rb');
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return is_readable($this->source);
    }

    /**
     * {@inheritdoc}
     */
    public function size(): int
    {
        return (int)filesize($this->source);
    }
}
