<?php


namespace Plank\Mediable\Exceptions;

class ImageManipulationException extends \Exception
{
    public static function invalidMediaType(?string $type): self
    {
        return new self(
            "Cannot manipulate media with an aggregate type other than 'image', got '{$type}'."
        );
    }

    public static function unknownVariant(string $variantName): self
    {
        return new self(
            "Unknown variant '{$variantName}'."
        );
    }

    public static function unknownOutputFormat(): self
    {
        return new self(
            "Unable to determine valid output format for file."
        );
    }

    public static function fileExists(string $path): self
    {
        return new static("A file already exists at `{$path}`.");
    }
}
