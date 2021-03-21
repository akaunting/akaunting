<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\ReturnTypes;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ArrayType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\IntegerType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

/**
 * @internal
 */
final class RequestExtension implements DynamicMethodReturnTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function getClass(): string
    {
        return Request::class;
    }

    /**
     * {@inheritdoc}
     */
    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): Type {
        $uploadedFileType = new ObjectType(UploadedFile::class);
        $uploadedFileArrayType = new ArrayType(new IntegerType(), $uploadedFileType);

        if (count($methodCall->args) === 0) {
            return new ArrayType(new IntegerType(), $uploadedFileType);
        }

        if (count($methodCall->args) === 1) {
            return TypeCombinator::union($uploadedFileArrayType, TypeCombinator::addNull($uploadedFileType));
        }

        return TypeCombinator::union(TypeCombinator::union($uploadedFileArrayType, $uploadedFileType), $scope->getType($methodCall->args[1]->value));
    }
}
