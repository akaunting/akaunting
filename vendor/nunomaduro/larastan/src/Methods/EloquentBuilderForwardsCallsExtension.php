<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Methods;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use NunoMaduro\Larastan\Concerns;
use NunoMaduro\Larastan\Reflection\EloquentBuilderMethodReflection;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\Generic\TemplateMixedType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

final class EloquentBuilderForwardsCallsExtension implements MethodsClassReflectionExtension, BrokerAwareExtension
{
    use Concerns\HasBroker;

    /**
     * The methods that should be returned from query builder.
     *
     * @var string[]
     */
    protected $passthru = [
        'insert', 'insertOrIgnore', 'insertGetId', 'insertUsing', 'getBindings', 'toSql', 'dump', 'dd',
        'exists', 'doesntExist', 'count', 'min', 'max', 'avg', 'average', 'sum', 'getConnection',
    ];

    /** @var BuilderHelper */
    private $builderHelper;

    public function __construct(BuilderHelper $builderHelper)
    {
        $this->builderHelper = $builderHelper;
    }

    private function getBuilderReflection(): ClassReflection
    {
        return $this->broker->getClass(QueryBuilder::class);
    }

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        return $this->findMethod($classReflection, $methodName) !== null;
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        $methodReflection = $this->findMethod($classReflection, $methodName);

        if ($methodReflection === null) {
            throw new ShouldNotHappenException(sprintf("'%s' not found in %s", $methodName, $classReflection->getName()));
        }

        return $methodReflection;
    }

    private function findMethod(ClassReflection $classReflection, string $methodName): ?MethodReflection
    {
        if ($classReflection->getName() !== EloquentBuilder::class && ! $classReflection->isSubclassOf(EloquentBuilder::class)) {
            return null;
        }

        if (in_array($methodName, $this->passthru, true)) {
            $methodReflection = $this->getBuilderReflection()->getNativeMethod($methodName);

            $parametersAcceptor = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants());
            $returnType = $parametersAcceptor->getReturnType();

            if ($returnType instanceof MixedType) {
                $returnType = $returnType->subtract(new ObjectType(EloquentBuilder::class));
            }

            return new EloquentBuilderMethodReflection(
                $methodName, $classReflection, $methodReflection,
                $parametersAcceptor->getParameters(),
                $returnType,
                $parametersAcceptor->isVariadic()
            );
        }

        $templateTypeMap = $classReflection->getActiveTemplateTypeMap();

        /** @var Type|ObjectType|TemplateMixedType|null $modelType */
        $modelType = $templateTypeMap->getType('TModelClass');

        if ($modelType === null) {
            $modelType = new ObjectType(Model::class);
        }

        if ($this->getBuilderReflection()->hasNativeMethod($methodName) && (! $modelType instanceof ObjectType)) {
            $methodReflection = $this->getBuilderReflection()->getNativeMethod($methodName);
            $builderClass = EloquentBuilder::class;

            if ($modelType instanceof TemplateMixedType) {
                /** @var string $builderClass */
                $builderClass = $modelType->getScope()->getClassName();
            }

            $parametersAcceptor = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants());

            return new EloquentBuilderMethodReflection(
                $methodName, $classReflection, $methodReflection,
                $parametersAcceptor->getParameters(),
                new GenericObjectType($builderClass, [$modelType]),
                $parametersAcceptor->isVariadic()
            );
        }

        if ($modelType instanceof ObjectType) {
            if ($classReflection->isSubclassOf(EloquentBuilder::class)) {
                $eloquentBuilderClass = $classReflection->getName();
            } else {
                $eloquentBuilderClass = $this->builderHelper->determineBuilderType($modelType->getClassName());
            }

            $returnMethodReflection = $this->builderHelper->getMethodReflectionFromBuilder(
                $classReflection,
                $methodName,
                $modelType->getClassName(),
                new GenericObjectType($eloquentBuilderClass, [new ObjectType($modelType->getClassName())])
            );

            if ($returnMethodReflection !== null) {
                return $returnMethodReflection;
            }
        }

        return null;
    }
}
