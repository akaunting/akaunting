<?php
declare(strict_types=1);

namespace Plank\Mediable;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\FilesystemManager;
use Plank\Mediable\Exceptions\MediaMoveException;
use Plank\Mediable\Helpers\File;

/**
 * Media Mover Class.
 */
class MediaMover
{
    /**
     * @var FilesystemManager
     */
    protected $filesystem;

    /**
     * Constructor.
     * @param FilesystemManager $filesystem
     */
    public function __construct(FilesystemManager $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Move the file to a new location on disk.
     *
     * Will invoke the `save()` method on the model after the associated file has been moved to prevent synchronization errors
     * @param  Media $media
     * @param  string $directory directory relative to disk root
     * @param  string $filename filename. Do not include extension
     * @return void
     * @throws MediaMoveException If attempting to change the file extension or a file with the same name already exists at the destination
     */
    public function move(Media $media, string $directory, string $filename = null): void
    {
        $storage = $this->filesystem->disk($media->disk);

        $filename = $this->cleanFilename($media, $filename);
        $directory = File::sanitizePath($directory);
        $targetPath = $directory . '/' . $filename . '.' . $media->extension;

        if ($storage->has($targetPath)) {
            throw MediaMoveException::destinationExists($targetPath);
        }

        $storage->move($media->getDiskPath(), $targetPath);

        $media->filename = $filename;
        $media->directory = $directory;
        $media->save();
    }

    /**
     * Move the file to a new location on another disk.
     *
     * Will invoke the `save()` method on the model after the associated file has been moved to prevent synchronization errors
     * @param  Media $media
     * @param  string $disk the disk to move the file to
     * @param  string $directory directory relative to disk root
     * @param  string $filename filename. Do not include extension
     * @param  array $options additional options to pass to the disk driver when uploading the file
     * @return void
     * @throws MediaMoveException If attempting to change the file extension or a file with the same name already exists at the destination
     */
    public function moveToDisk(
        Media $media,
        string $disk,
        string $directory,
        string $filename = null,
        array $options = []
    ): void {
        if ($media->disk === $disk) {
            $this->move($media, $directory, $filename);
            return;
        }

        $currentStorage = $this->filesystem->disk($media->disk);
        $targetStorage = $this->filesystem->disk($disk);

        $filename = $this->cleanFilename($media, $filename);
        $directory = File::sanitizePath($directory);
        $targetPath = $directory . '/' . $filename . '.' . $media->extension;

        if ($targetStorage->has($targetPath)) {
            throw MediaMoveException::destinationExistsOnDisk($disk, $targetPath);
        }

        try {
            if (!isset($options['visibility'])) {
                $options['visibility'] = $currentStorage->getVisibility($media->getDiskPath());
            }

            $targetStorage->put($targetPath, $currentStorage->readStream($media->getDiskPath()), $options);
            $currentStorage->delete($media->getDiskPath());
        } catch (FileNotFoundException $e) {
            throw MediaMoveException::fileNotFound($media->disk, $media->getDiskPath(), $e);
        }

        $media->disk = $disk;
        $media->filename = $filename;
        $media->directory = $directory;
        $media->save();
    }

    /**
     * Copy the file from one Media object to another one.
     *
     * This method creates a new Media object as well as duplicates the associated file on the disk.
     *
     * @param  Media $media The media to copy from
     * @param  string $directory directory relative to disk root
     * @param  string $filename optional filename. Do not include extension
     *
     * @return Media
     * @throws MediaMoveException If a file with the same name already exists at the destination or it fails to copy the file
     */
    public function copyTo(Media $media, string $directory, string $filename = null): Media
    {
        $storage = $this->filesystem->disk($media->disk);

        $filename = $this->cleanFilename($media, $filename);
        $directory = File::sanitizePath($directory);

        $targetPath = $directory . '/' . $filename . '.' . $media->extension;

        if ($storage->has($targetPath)) {
            throw MediaMoveException::destinationExists($targetPath);
        }

        try {
            $storage->copy($media->getDiskPath(), $targetPath);
        } catch (\Exception $e) {
            throw MediaMoveException::failedToCopy($media->getDiskPath(), $targetPath, $e);
        }

        // now we copy the Media object
        /** @var Media $newMedia */
        $newMedia = $media->replicate();
        $newMedia->filename = $filename;
        $newMedia->directory = $directory;

        $newMedia->save();

        return $newMedia;
    }

    /**
     * Copy the file from one Media object to another one on a different disk.
     *
     * This method creates a new Media object as well as duplicates the associated file on the disk.
     *
     * @param  Media $media The media to copy from
     * @param  string $disk the disk to copy the file to
     * @param  string $directory directory relative to disk root
     * @param  string $filename optional filename. Do not include extension
     *
     * @return Media
     * @throws MediaMoveException If a file with the same name already exists at the destination or it fails to copy the file
     */
    public function copyToDisk(
        Media $media,
        string $disk,
        string $directory,
        string $filename = null,
        array $options = []
    ): Media {
        if ($media->disk === $disk) {
            return $this->copyTo($media, $directory, $filename);
        }

        $currentStorage = $this->filesystem->disk($media->disk);
        $targetStorage = $this->filesystem->disk($disk);

        $filename = $this->cleanFilename($media, $filename);
        $directory = File::sanitizePath($directory);
        $targetPath = $directory . '/' . $filename . '.' . $media->extension;

        if ($targetStorage->has($targetPath)) {
            throw MediaMoveException::destinationExistsOnDisk($disk, $targetPath);
        }

        try {
            if (!isset($options['visibility'])) {
                $options['visibility'] = $currentStorage->getVisibility($media->getDiskPath());
            }
            $targetStorage->put($targetPath, $currentStorage->readStream($media->getDiskPath()), $options);
        } catch (FileNotFoundException $e) {
            throw MediaMoveException::fileNotFound($media->disk, $media->getDiskPath(), $e);
        }

        // now we copy the Media object
        /** @var Media $newMedia */
        $newMedia = $media->replicate();
        $newMedia->disk = $disk;
        $newMedia->filename = $filename;
        $newMedia->directory = $directory;

        $newMedia->save();

        return $newMedia;
    }

    protected function cleanFilename(Media $media, ?string $filename): string
    {
        if ($filename) {
            return File::sanitizeFileName(
                $this->removeExtensionFromFilename($filename, $media->extension)
            );
        }

        return $media->filename;
    }

    /**
     * Remove the media's extension from a filename.
     * @param  string $filename
     * @param  string $extension
     * @return string
     */
    protected function removeExtensionFromFilename(string $filename, string $extension): string
    {
        $extension = '.' . $extension;
        $extensionLength = mb_strlen($filename) - mb_strlen($extension);
        if (mb_strrpos($filename, $extension) === $extensionLength) {
            $filename = mb_substr($filename, 0, $extensionLength);
        }

        return $filename;
    }
}
