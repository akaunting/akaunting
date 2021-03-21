<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Properties;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;

/**
 * @internal
 */
final class ModelAccessorExtension implements PropertiesClassReflectionExtension
{
    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        if (! $classReflection->isSubclassOf(Model::class)) {
            return false;
        }

        return $classReflection->hasNativeMethod('get'.Str::studly($propertyName).'Attribute');
    }

    public function getProperty(
        ClassReflection $classReflection,
        string $propertyName
    ): PropertyReflection {
        $method = $classReflection->getNativeMethod('get'.Str::studly($propertyName).'Attribute');

        return new ModelProperty(
            $classReflection,
            $method->getVariants()[0]->getReturnType(),
            $method->getVariants()[0]->getReturnType()
        );
    }
}
