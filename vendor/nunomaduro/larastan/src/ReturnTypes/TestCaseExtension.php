<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\ReturnTypes;

use Illuminate\Foundation\Testing\TestCase;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

/**
 * @internal
 */
final class TestCaseExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return TestCase::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return in_array($methodReflection->getName(), [
            'mock',
            'partialMock',
            'spy',
        ], true);
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): Type {
        $defaultReturnType = new ObjectType('Mockery\\MockInterface');

        $classType = $scope->getType($methodCall->args[0]->value);

        if (! $classType instanceof ConstantStringType) {
            return $defaultReturnType;
        }

        $objectType = new ObjectType($classType->getValue());

        return TypeCombinator::intersect($defaultReturnType, $objectType);
    }
}
