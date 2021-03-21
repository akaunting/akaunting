<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Methods;

use Illuminate\Database\Eloquent\Model;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\StaticType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeTraverser;
use PHPStan\Type\TypeWithClassName;

final class ModelTypeHelper
{
    public static function replaceStaticTypeWithModel(Type $type, string $modelClass): Type
    {
        return TypeTraverser::map($type, static function (Type $type, callable $traverse) use ($modelClass): Type {
            if ($type instanceof ObjectWithoutClassType || $type instanceof StaticType) {
                return new ObjectType($modelClass);
            }

            if ($type instanceof TypeWithClassName && $type->getClassName() === Model::class) {
                return new ObjectType($modelClass);
            }

            return $traverse($type);
        });
    }
}
