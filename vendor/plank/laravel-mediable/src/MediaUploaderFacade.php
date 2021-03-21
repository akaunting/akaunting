<?php
declare(strict_types=1);

namespace Plank\Mediable;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for Media Uploader.
 *
 * @method static MediaUploader fromSource(mixed $source)
 * @method static MediaUploader fromString(string $source)
 * @method static MediaUploader toDestination($disk, $directory)
 * @method static MediaUploader toDisk($disk)
 * @method static MediaUploader toDirectory($directory)
 * @method static MediaUploader useFilename($filename)
 * @method static MediaUploader useHashForFilename()
 * @method static MediaUploader useOriginalFilename()
 * @method static MediaUploader setModelClass($class)
 * @method static MediaUploader setMaximumSize($size)
 * @method static MediaUploader setOnDuplicateBehavior($behavior)
 * @method static string getOnDuplicateBehavior()
 * @method static MediaUploader onDuplicateError()
 * @method static MediaUploader onDuplicateIncrement()
 * @method static MediaUploader onDuplicateReplace()
 * @method static MediaUploader onDuplicateUpdate()
 * @method static MediaUploader setStrictTypeChecking($strict)
 * @method static MediaUploader setAllowUnrecognizedTypes($allow)
 * @method static MediaUploader setTypeDefinition($type, $mime_types, $extensions)
 * @method static MediaUploader setAllowedMimeTypes($allowed_mimes)
 * @method static MediaUploader setAllowedExtensions($allowed_extensions)
 * @method static MediaUploader setAllowedAggregateTypes($allowed_types)
 * @method static string inferAggregateType($mime_type, $extension)
 * @method static string[] possibleAggregateTypesForMimeType($mime)
 * @method static string[] possibleAggregateTypesForExtension($extension)
 * @method static Media upload()
 * @method static Media replace(Media $media)
 * @method static MediaUploader beforeSave(callable $callable)
 * @method static Media importPath($disk, $path)
 * @method static Media import($disk, $directory, $filename, $extension)
 * @method static bool update(Media $media)
 * @method static void verifyFile()
 */
class MediaUploaderFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mediable.uploader';
    }
}
