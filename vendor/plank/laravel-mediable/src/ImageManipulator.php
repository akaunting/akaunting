<?php

namespace Plank\Mediable;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Collection;
use Intervention\Image\ImageManager;
use Plank\Mediable\Exceptions\ImageManipulationException;
use Psr\Http\Message\StreamInterface;

class ImageManipulator
{
    private ImageManager $imageManager;

    /**
     * @var ImageManipulation[]
     */
    private array $variantDefinitions = [];

    private array $variantDefinitionGroups = [];

    /**
     * @var FilesystemManager
     */
    private $filesystem;

    public function __construct(ImageManager $imageManager, FilesystemManager $filesystem)
    {
        $this->imageManager = $imageManager;
        $this->filesystem = $filesystem;
    }

    public function defineVariant(
        string $variantName,
        ImageManipulation $manipulation,
        ?array $tags = []
    ) {
        $this->variantDefinitions[$variantName] = $manipulation;
        foreach ($tags as $tag) {
            $this->variantDefinitionGroups[$tag][] = $variantName;
        }
    }

    public function hasVariantDefinition(string $variantName): bool
    {
        return isset($this->variantDefinitions[$variantName]);
    }

    /**
     * @param string $variantName
     * @return ImageManipulation
     * @throws ImageManipulationException if Variant is not defined
     */
    public function getVariantDefinition(string $variantName): ImageManipulation
    {
        if (isset($this->variantDefinitions[$variantName])) {
            return $this->variantDefinitions[$variantName];
        }

        throw ImageManipulationException::unknownVariant($variantName);
    }

    public function getAllVariantDefinitions(): Collection
    {
        return collect($this->variantDefinitions);
    }

    public function getAllVariantNames(): array
    {
        return array_keys($this->variantDefinitions);
    }

    public function getVariantDefinitionsByTag(string $tag): Collection
    {
        return $this->getAllVariantDefinitions()
            ->intersectByKeys(array_flip($this->getVariantNamesByTag($tag)));
    }

    public function getVariantNamesByTag(string $tag): array
    {
        return $this->variantDefinitionGroups[$tag] ?? [];
    }

    /**
     * @param Media $media
     * @param string $variantName
     * @param bool $forceRecreate
     * @return Media
     * @throws ImageManipulationException
     * @throws \Illuminate\Contracts\Filesystem\FileExistsException
     */
    public function createImageVariant(
        Media $media,
        string $variantName,
        bool $forceRecreate = false
    ): Media {
        $this->validateMedia($media);

        $modelClass = config('mediable.model');
        /** @var Media $variant */
        $variant = new $modelClass();
        $recreating = false;
        $originalVariant = null;

        // don't recreate if that variant already exists for the model
        if ($media->hasVariant($variantName)) {
            $variant = $media->findVariant($variantName);
            if ($forceRecreate) {
                // replace the existing variant
                $recreating = true;
                $originalVariant = clone $variant;
            } else {
                // variant already exists, nothing more to do
                return $variant;
            }
        }

        $manipulation = $this->getVariantDefinition($variantName);

        $outputFormat = $this->determineOutputFormat($manipulation, $media);
        $image = $this->imageManager->make($media->contents());

        $callback = $manipulation->getCallback();
        $callback($image, $media);

        $outputStream = $image->stream(
            $outputFormat,
            $manipulation->getOutputQuality()
        );

        $variant->variant_name = $variantName;
        $variant->original_media_id = $media->isOriginal()
            ? $media->getKey()
            : $media->original_media_id; // attach variants of variants to the same original

        $variant->disk = $manipulation->getDisk() ?? $media->disk;
        $variant->directory = $manipulation->getDirectory() ?? $media->directory;
        $variant->filename = $this->determineFilename(
            $media->findOriginal(),
            $manipulation,
            $variant,
            $outputStream
        );
        $variant->extension = $outputFormat;
        $variant->mime_type = $this->getMimeTypeForOutputFormat($outputFormat);
        $variant->aggregate_type = Media::TYPE_IMAGE;
        $variant->size = $outputStream->getSize();

        $this->checkForDuplicates($variant, $manipulation, $originalVariant);
        if ($beforeSave = $manipulation->getBeforeSave()) {
            $beforeSave($variant, $originalVariant);
            // destination may have been changed, check for duplicates again
            $this->checkForDuplicates($variant, $manipulation, $originalVariant);
        }

        if ($recreating) {
            // delete the original file for that variant
            $this->filesystem->disk($originalVariant->disk)
                ->delete($originalVariant->getDiskPath());
        }

        $visibility = $manipulation->getVisibility();
        if ($visibility == 'match') {
            $visibility = ($media->isVisible() ? 'public' : 'private');
        }
        $options = [];
        if ($visibility) {
            $options = ['visibility' => $visibility];
        }

        $this->filesystem->disk($variant->disk)
            ->writeStream(
                $variant->getDiskPath(),
                $outputStream->detach(),
                $options
            );

        $variant->save();

        return $variant;
    }

    private function getMimeTypeForOutputFormat(string $outputFormat): string
    {
        return ImageManipulation::MIME_TYPE_MAP[$outputFormat];
    }

    /**
     * @param ImageManipulation $manipulation
     * @param Media $media
     * @return string
     * @throws ImageManipulationException If output format cannot be determined
     */
    private function determineOutputFormat(
        ImageManipulation $manipulation,
        Media $media
    ): string {
        if ($format = $manipulation->getOutputFormat()) {
            return $format;
        }

        // attempt to infer the format from the mime type
        $mime = strtolower($media->mime_type);
        $format = array_search($mime, ImageManipulation::MIME_TYPE_MAP);
        if ($format !== false) {
            return $format;
        }

        // attempt to infer the format from the file extension
        $extension = strtolower($media->extension);
        if (in_array($extension, ImageManipulation::VALID_IMAGE_FORMATS)) {
            return $extension;
        }
        if ($extension === 'jpeg') {
            return ImageManipulation::FORMAT_JPG;
        }
        if ($extension === 'tiff') {
            return ImageManipulation::FORMAT_TIFF;
        }

        throw ImageManipulationException::unknownOutputFormat();
    }

    public function determineFilename(
        Media $originalMedia,
        ImageManipulation $manipulation,
        Media $variant,
        StreamInterface $stream
    ): string {
        if ($filename = $manipulation->getFilename()) {
            return $filename;
        }

        if ($manipulation->usingHashForFilename()) {
            return $this->getHashFromStream($stream);
        }
        return sprintf('%s-%s', $originalMedia->filename, $variant->variant_name);
    }

    public function validateMedia(Media $media)
    {
        if ($media->aggregate_type != Media::TYPE_IMAGE) {
            throw ImageManipulationException::invalidMediaType($media->aggregate_type);
        }
    }

    private function getHashFromStream(StreamInterface $stream): string
    {
        $stream->rewind();
        $hash = hash_init('md5');
        while ($chunk = $stream->read(64)) {
            hash_update($hash, $chunk);
        }
        $filename = hash_final($hash);
        $stream->rewind();

        return $filename;
    }

    private function checkForDuplicates(
        Media $variant,
        ImageManipulation $manipulation,
        Media $originalVariant = null
    ) {
        if ($originalVariant
            && $variant->disk === $originalVariant->disk
            && $variant->getDiskPath() === $originalVariant->getDiskPath()
        ) {
            // same as the original, no conflict as we are going to replace the file anyways
            return;
        }

        if (!$this->filesystem->disk($variant->disk)->has($variant->getDiskPath())) {
            // no conflict, carry on
            return;
        }

        switch ($manipulation->getOnDuplicateBehaviour()) {
            case ImageManipulation::ON_DUPLICATE_ERROR:
                throw ImageManipulationException::fileExists($variant->getDiskPath());

            case ImageManipulation::ON_DUPLICATE_INCREMENT:
            default:
                $variant->filename = $this->generateUniqueFilename($variant);
                break;
        }
    }

    /**
     * Increment model's filename until one is found that doesn't already exist.
     * @param Media $model
     * @return string
     */
    private function generateUniqueFilename(Media $model): string
    {
        $storage = $this->filesystem->disk($model->disk);
        $counter = 0;
        do {
            $filename = "{$model->filename}";
            if ($counter > 0) {
                $filename .= '-' . $counter;
            }
            $path = "{$model->directory}/{$filename}.{$model->extension}";
            ++$counter;
        } while ($storage->has($path));

        return $filename;
    }
}
