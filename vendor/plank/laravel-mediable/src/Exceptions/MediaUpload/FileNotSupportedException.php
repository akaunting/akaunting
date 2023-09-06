<?php
declare(strict_types=1);

namespace Plank\Mediable\Exceptions\MediaUpload;

use Plank\Mediable\Exceptions\MediaUploadException;

class FileNotSupportedException extends MediaUploadException
{
    public static function strictTypeMismatch(string $mime, string $ext): self
    {
        return new static("File with mime of `{$mime}` not recognized for extension `{$ext}`.");
    }

    public static function unrecognizedFileType(string $mime, string $ext): self
    {
        return new static("File with mime of `{$mime}` and extension `{$ext}` is not recognized.");
    }

    public static function mimeRestricted(string $mime, array $allowed_mimes): self
    {
        $allowed = implode('`, `', $allowed_mimes);

        return new static("Cannot upload file with MIME type `{$mime}`. Only the `{$allowed}` MIME type(s) are permitted.");
    }

    public static function extensionRestricted(string $extension, array $allowed_extensions): self
    {
        $allowed = implode('`, `', $allowed_extensions);

        return new static("Cannot upload file with extension `{$extension}`. Only the `{$allowed}` extension(s) are permitted.");
    }

    public static function aggregateTypeRestricted(string $type, array $allowed_types): self
    {
        $allowed = implode('`, `', $allowed_types);

        return new static("Cannot upload file of aggregate type `{$type}`. Only files of type(s) `{$allowed}` are permitted.");
    }
}
