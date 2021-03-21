<?php
declare(strict_types=1);

namespace Plank\Mediable\UrlGenerators;

use Plank\Mediable\Exceptions\MediaUrlException;
use Plank\Mediable\Media;

interface UrlGeneratorInterface
{
    /**
     * Set the media instance for which urls are being generated.
     * @param \Plank\Mediable\Media $media
     */
    public function setMedia(Media $media): void;

    /**
     * Retrieve the absolute path to the file.
     *
     * For local files this should return a path
     * For remote files this should return a url
     * @return string
     *
     * @throws MediaUrlException
     */
    public function getAbsolutePath(): string;

    /**
     * Check if the file is publicly accessible.
     *
     * Disks configs should indicate this with the visibility key
     * @return bool
     */
    public function isPubliclyAccessible(): bool;

    /**
     * Get a Url to the file.
     * @return string
     *
     * @throws MediaUrlException
     */
    public function getUrl(): string;
}
