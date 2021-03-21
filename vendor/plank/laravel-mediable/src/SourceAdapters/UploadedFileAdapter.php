<?php
declare(strict_types=1);

namespace Plank\Mediable\SourceAdapters;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Uploaded File Adapter.
 *
 * Adapts the UploadedFile class from Symfony Components.
 */
class UploadedFileAdapter implements SourceAdapterInterface
{
    /**
     * The source object.
     * @var UploadedFile
     */
    protected $source;

    /**
     * Constructor.
     * @param UploadedFile $source
     */
    public function __construct(UploadedFile $source)
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
        return $this->source->getPath() . '/' . $this->source->getFilename();
    }

    /**
     * {@inheritdoc}
     */
    public function filename(): string
    {
        return pathinfo((string)$this->source->getClientOriginalName(), PATHINFO_FILENAME);
    }

    /**
     * {@inheritdoc}
     */
    public function extension(): string
    {
        $extension = $this->source->getClientOriginalExtension();

        if ($extension) {
            return $extension;
        }

        return (string)$this->source->guessExtension();
    }

    /**
     * {@inheritdoc}
     */
    public function mimeType(): string
    {
        return (string)$this->source->getClientMimeType();
    }

    /**
     * {@inheritdoc}
     */
    public function contents(): string
    {
        return (string)file_get_contents($this->path());
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
        return $this->source->isValid();
    }

    /**
     * {@inheritdoc}
     */
    public function size(): int
    {
        return (int)$this->source->getSize();
    }
}
