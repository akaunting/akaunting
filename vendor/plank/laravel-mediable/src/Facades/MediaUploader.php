<?php
declare(strict_types=1);

namespace Plank\Mediable\Facades;

use Illuminate\Support\Facades\Facade;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploader as Uploader;

/**
 * Facade for Media Uploader.
 *
 * @method static Uploader fromSource(mixed $source)
 * @method static Uploader fromString(string $source)
 * @method static Uploader toDestination(string $disk, string $directory)
 * @method static Uploader toDisk(string $disk)
 * @method static Uploader toDirectory(string $directory)
 * @method static Uploader useFilename(string $filename)
 * @method static Uploader useHashForFilename()
 * @method static Uploader useOriginalFilename()
 * @method static Uploader setModelClass(string $class)
 * @method static Uploader setMaximumSize(int $size)
 * @method static Uploader setOnDuplicateBehavior(string $behavior)
 * @method static string getOnDuplicateBehavior()
 * @method static Uploader onDuplicateError()
 * @method static Uploader onDuplicateIncrement()
 * @method static Uploader onDuplicateReplace()
 * @method static Uploader onDuplicateReplaceWithVariants()
 * @method static Uploader onDuplicateUpdate()
 * @method static Uploader setStrictTypeChecking(bool $strict)
 * @method static Uploader setAllowUnrecognizedTypes(bool $allow)
 * @method static Uploader setTypeDefinition(string $type, array $mime_types, array $extensions)
 * @method static Uploader setAllowedMimeTypes(array $allowed_mimes)
 * @method static Uploader setAllowedExtensions(array $allowed_extensions)
 * @method static Uploader setAllowedAggregateTypes(array $allowed_types)
 * @method static Uploader makePublic()
 * @method static Uploader makePrivate()
 * @method static string inferAggregateType(string $mime_type, string $extension)
 * @method static string[] possibleAggregateTypesForMimeType(string $mime)
 * @method static string[] possibleAggregateTypesForExtension(string $extension)
 * @method static Media upload()
 * @method static Media replace(Media $media)
 * @method static Media populateModel(Media $model)
 * @method static Uploader beforeSave(callable $callable)
 * @method static Media importPath(string $disk, string $path)
 * @method static Media import(string $disk, string $directory, string $filename, string $extension)
 * @method static bool update(Media $media)
 * @method static void verifyFile()
 */
class MediaUploader extends Facade
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

    public static function getFacadeRoot()
    {
        // prevent the facade from behaving like a singleton
        if (!self::isMock()) {
            self::clearResolvedInstance('mediable.uploader');
        }
        return parent::getFacadeRoot();
    }
}
