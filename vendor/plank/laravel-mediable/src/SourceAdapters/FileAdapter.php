<?php
declare(strict_types=1);

namespace Plank\Mediable\SourceAdapters;

use Plank\Mediable\Helpers\File as FileHelper;
use Symfony\Component\HttpFoundation\File\File;

/**
 * File Adapter.
 *
 * Adapts the File class from Symfony Components
 */
class FileAdapter implements SourceAdapterInterface
{
    /**
     * The source object.
     * @var \Symfony\Component\HttpFoundation\File\File
     */
    protected $source;

    /**
     * Constructor.
     * @param \Symfony\Component\HttpFoundation\File\File $source
     */
    public function __construct(File $source)
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
        return $this->source->getPath() . '/' . $this->source->getFilename();
    }

    /**
     * {@inheritdoc}
     */
    public function filename(): string
    {
        return pathinfo($this->source->getFilename(), PATHINFO_FILENAME);
    }

    /**
     * {@inheritdoc}
     */
    public function extension(): string
    {
        $extension = pathinfo($this->path(), PATHINFO_EXTENSION);

        if ($extension) {
            return $extension;
        }

        return (string)FileHelper::guessExtension($this->mimeType());
    }

    /**
     * {@inheritdoc}
     */
    public function mimeType(): string
    {
        return (string)$this->source->getMimeType();
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
        return file_exists($this->path());
    }

    /**
     * {@inheritdoc}
     */
    public function size(): int
    {
        return (int)filesize($this->path());
    }
}
