<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Types;

use function count;
use Illuminate\Database\Eloquent\Collection;
use PHPStan\Analyser\NameScope;
use PHPStan\PhpDoc\TypeNodeResolverExtension;
use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

/**
 * @see https://github.com/nunomaduro/larastan/issues/476
 * @see https://gist.github.com/ondrejmirtes/56af016d0595788d5400b8dfb6520adc
 *
 * This extension interprets docblocks like:
 *
 * \Illuminate\Database\Eloquent\Collection|\App\Account[] $accounts
 *
 * and transforms them into:
 *
 * \Illuminate\Database\Eloquent\Collection<\App\Account> $accounts
 *
 * Now IDE's can benefit from auto-completion, and we can benefit from the correct type passed to the generic collection
 */
class GenericEloquentCollectionTypeNodeResolverExtension implements TypeNodeResolverExtension
{
    public function resolve(TypeNode $typeNode, NameScope $nameScope): ?Type
    {
        if (! $typeNode instanceof UnionTypeNode || count($typeNode->types) !== 2) {
            return null;
        }

        $arrayTypeNode = null;
        $identifierTypeNode = null;
        foreach ($typeNode->types as $innerTypeNode) {
            if ($innerTypeNode instanceof ArrayTypeNode) {
                $arrayTypeNode = $innerTypeNode;
                continue;
            }

            if ($innerTypeNode instanceof IdentifierTypeNode) {
                $identifierTypeNode = $innerTypeNode;
            }
        }

        if ($arrayTypeNode === null || $identifierTypeNode === null) {
            return null;
        }

        $identifierTypeName = $nameScope->resolveStringName($identifierTypeNode->name);
        if ($identifierTypeName !== Collection::class) {
            return null;
        }

        $innerArrayTypeNode = $arrayTypeNode->type;
        if (! $innerArrayTypeNode instanceof IdentifierTypeNode) {
            return null;
        }

        $innerArrayTypeName = $nameScope->resolveStringName($innerArrayTypeNode->name);

        return new GenericObjectType($identifierTypeName, [
            new ObjectType($innerArrayTypeName),
        ]);
    }
}
