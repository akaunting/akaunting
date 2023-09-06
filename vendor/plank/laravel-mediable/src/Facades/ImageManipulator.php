<?php

namespace Plank\Mediable\Facades;

use Illuminate\Support\Facades\Facade;
use Plank\Mediable\ImageManipulation;
use Plank\Mediable\Media;

/**
 * @method static defineVariant(string $variantName, ImageManipulation $manipulation)
 * @method static bool hasVariantDefinition(string $variantName)
 * @method static ImageManipulation getVariantDefinition(string $variantName)
 * @method static Media createImageVariant(Media $media, string $variantName, bool $forceRecreate = false)
 * @method static validateMedia(Media $media)
 */
class ImageManipulator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Plank\Mediable\ImageManipulator::class;
    }
}
