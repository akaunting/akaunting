<?php
declare(strict_types=1);

namespace Plank\Mediable\Exceptions\MediaUpload;

use Plank\Mediable\Exceptions\MediaUploadException;

class FileExistsException extends MediaUploadException
{
    public static function fileExists(string $path): self
    {
        return new static("A file already exists at `{$path}`.");
    }
}
