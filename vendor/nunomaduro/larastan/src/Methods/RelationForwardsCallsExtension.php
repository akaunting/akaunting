<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Methods;

use Illuminate\Database\Eloquent\Relations\Relation;
use NunoMaduro\Larastan\Reflection\EloquentBuilderMethodReflection;
use PHPStan\Analyser\OutOfClassScope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\ObjectType;

final class RelationForwardsCallsExtension implements MethodsClassReflectionExtension
{
    /** @var BuilderHelper */
    private $builderHelper;

    public function __construct(BuilderHelper $builderHelper)
    {
        $this->builderHelper = $builderHelper;
    }

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if (! $classReflection->isSubclassOf(Relation::class)) {
            return false;
        }

        /** @var ObjectType|null $relatedModel */
        $relatedModel = $classReflection->getActiveTemplateTypeMap()->getType('TRelatedModel');

        if ($relatedModel === null) {
            return false;
        }

        $returnMethodReflection = $this->builderHelper->getMethodReflectionFromBuilder(
            $classReflection,
            $methodName,
            $relatedModel->getClassName(),
            new GenericObjectType($classReflection->getName(), [$relatedModel])
        );

        if ($returnMethodReflection === null) {
            return $relatedModel->hasMethod($methodName)->yes();
        }

        return true;
    }

    public function getMethod(
        ClassReflection $classReflection,
        string $methodName
    ): MethodReflection {
        /** @var ObjectType|null $relatedModel */
        $relatedModel = $classReflection->getActiveTemplateTypeMap()->getType('TRelatedModel');

        if ($relatedModel === null) {
            throw new ShouldNotHappenException(sprintf('%s does not have TRelatedModel template type. But it should.', $classReflection->getName()));
        }

        $returnMethodReflection = $this->builderHelper->getMethodReflectionFromBuilder(
            $classReflection,
            $methodName,
            $relatedModel->getClassName(),
            new GenericObjectType($classReflection->getName(), [$relatedModel])
        );

        if ($returnMethodReflection === null) {
            $originalMethodReflection = $relatedModel->getMethod($methodName, new OutOfClassScope());

            $returnMethodReflection = new EloquentBuilderMethodReflection(
                $methodName, $classReflection, $originalMethodReflection,
                ParametersAcceptorSelector::selectSingle($originalMethodReflection->getVariants())->getParameters(),
                new GenericObjectType($classReflection->getName(), [$relatedModel]),
                false
            );
        }

        return $returnMethodReflection;
    }
}
