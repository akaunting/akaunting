<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Types;

use PHPStan\Analyser\NameScope;
use PHPStan\PhpDoc\TypeNodeResolverExtension;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Type;

/**
 * Ensures a 'view-string' type in PHPDoc is recognised to be of type ViewStringType.
 */
class ViewStringTypeNodeResolverExtension implements TypeNodeResolverExtension
{
    public function resolve(TypeNode $typeNode, NameScope $nameScope): ?Type
    {
        if ($typeNode instanceof IdentifierTypeNode && $typeNode->__toString() === 'view-string') {
            return new ViewStringType();
        }

        return null;
    }
}
