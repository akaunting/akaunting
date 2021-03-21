<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Types;

use function count;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use PHPStan\Analyser\NameScope;
use PHPStan\PhpDoc\TypeNodeResolverExtension;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class GenericEloquentBuilderTypeNodeResolverExtension implements TypeNodeResolverExtension
{
    public function resolve(TypeNode $typeNode, NameScope $nameScope): ?Type
    {
        if (! $typeNode instanceof UnionTypeNode || count($typeNode->types) !== 2) {
            return null;
        }

        $modelTypeNode = null;
        $builderTypeNode = null;
        foreach ($typeNode->types as $innerTypeNode) {
            if ($innerTypeNode instanceof IdentifierTypeNode
                && is_subclass_of($nameScope->resolveStringName($innerTypeNode->name), Model::class)
            ) {
                $modelTypeNode = $innerTypeNode;
                continue;
            }

            if (
                $innerTypeNode instanceof IdentifierTypeNode
                && ($nameScope->resolveStringName($innerTypeNode->name) === Builder::class || is_subclass_of($nameScope->resolveStringName($innerTypeNode->name), Builder::class))
            ) {
                $builderTypeNode = $innerTypeNode;
            }
        }

        if ($modelTypeNode === null || $builderTypeNode === null) {
            return null;
        }

        $builderTypeName = $nameScope->resolveStringName($builderTypeNode->name);
        $modelTypeName = $nameScope->resolveStringName($modelTypeNode->name);

        return new GenericObjectType($builderTypeName, [
            new ObjectType($modelTypeName),
        ]);
    }
}
