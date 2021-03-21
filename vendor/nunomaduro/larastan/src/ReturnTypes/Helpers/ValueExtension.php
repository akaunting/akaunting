<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\ReturnTypes\Helpers;

use function count;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\DynamicFunctionReturnTypeExtension;
use PHPStan\Type\NeverType;
use PHPStan\Type\Type;

/**
 * @internal
 */
final class ValueExtension implements DynamicFunctionReturnTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function isFunctionSupported(FunctionReflection $functionReflection): bool
    {
        return $functionReflection->getName() === 'value';
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeFromFunctionCall(
        FunctionReflection $functionReflection,
        FuncCall $functionCall,
        Scope $scope
    ): Type {
        if (count($functionCall->args) === 0) {
            return new NeverType();
        }

        $arg = $functionCall->args[0]->value;
        if ($arg instanceof Closure) {
            $callbackType = $scope->getType($arg);
            $callbackReturnType = ParametersAcceptorSelector::selectFromArgs(
                $scope,
                $functionCall->args,
                $callbackType->getCallableParametersAcceptors($scope)
            )->getReturnType();

            return $callbackReturnType;
        }

        return $scope->getType($arg);
    }
}
