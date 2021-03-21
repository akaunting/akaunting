<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Methods;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Str;
use NunoMaduro\Larastan\Reflection\EloquentBuilderMethodReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariantWithPhpDocs;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MissingMethodFromReflectionException;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\Php\DummyParameter;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\ShouldNotHappenException;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\Generic\TemplateTypeHelper;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;
use PHPStan\Type\VoidType;

class BuilderHelper
{
    /** @var string[] */
    public const MODEL_RETRIEVAL_METHODS = ['first', 'find', 'findMany', 'findOrFail', 'firstOrFail'];

    /** @var string[] */
    public const MODEL_CREATION_METHODS = ['make', 'create', 'forceCreate', 'findOrNew', 'firstOrNew', 'updateOrCreate', 'firstOrCreate'];

    /** @var ReflectionProvider */
    private $reflectionProvider;

    /** @var bool */
    private $checkProperties;

    public function __construct(ReflectionProvider $reflectionProvider, bool $checkProperties)
    {
        $this->reflectionProvider = $reflectionProvider;
        $this->checkProperties = $checkProperties;
    }

    public function dynamicWhere(
        string $methodName,
        Type $returnObject
    ): ?EloquentBuilderMethodReflection {
        if (! Str::startsWith($methodName, 'where')) {
            return null;
        }

        if ($returnObject instanceof GenericObjectType && $this->checkProperties) {
            $returnClassReflection = $returnObject->getClassReflection();

            if ($returnClassReflection !== null) {
                $modelType = $returnClassReflection->getActiveTemplateTypeMap()->getType('TModelClass');

                if ($modelType === null) {
                    $modelType = $returnClassReflection->getActiveTemplateTypeMap()->getType('TRelatedModel');
                }

                if ($modelType !== null) {
                    $finder = substr($methodName, 5);

                    $segments = preg_split(
                        '/(And|Or)(?=[A-Z])/', $finder, -1, PREG_SPLIT_DELIM_CAPTURE
                    );

                    if ($segments !== false) {
                        $trinaryLogic = TrinaryLogic::createYes();

                        foreach ($segments as $segment) {
                            if ($segment !== 'And' && $segment !== 'Or') {
                                $trinaryLogic = $trinaryLogic->and($modelType->hasProperty(Str::snake($segment)));
                            }
                        }

                        if (! $trinaryLogic->yes()) {
                            return null;
                        }
                    }
                }
            }
        }

        $classReflection = $this->reflectionProvider->getClass(QueryBuilder::class);

        $methodReflection = $classReflection->getNativeMethod('dynamicWhere');

        /** @var FunctionVariantWithPhpDocs $originalDynamicWhereVariant */
        $originalDynamicWhereVariant = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants());

        $originalParameter = $originalDynamicWhereVariant->getParameters()[1];

        $actualParameter = new DummyParameter($originalParameter->getName(), new MixedType(), $originalParameter->isOptional(), $originalParameter->passedByReference(), $originalParameter->isVariadic(), $originalParameter->getDefaultValue());

        return new EloquentBuilderMethodReflection(
            $methodName,
            $classReflection,
            $methodReflection,
            [$actualParameter],
            $returnObject,
            true
        );
    }

    public function searchOnEloquentBuilder(ClassReflection $eloquentBuilder, string $methodName, string $modelClassName): ?MethodReflection
    {
        $model = $this->reflectionProvider->getClass($modelClassName);

        if ($model->hasNativeMethod('scope'.ucfirst($methodName))) {
            $methodReflection = $model->getNativeMethod('scope'.ucfirst($methodName));
            $parametersAcceptor = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants());

            $parameters = $parametersAcceptor->getParameters();
            // We shift the parameters,
            // because first parameter is the Builder
            array_shift($parameters);

            $returnType = $parametersAcceptor->getReturnType();

            return new EloquentBuilderMethodReflection(
                'scope'.ucfirst($methodName),
                $methodReflection->getDeclaringClass(),
                $methodReflection,
                $parameters,
                $returnType,
                $parametersAcceptor->isVariadic()
            );
        }

        if (! $eloquentBuilder->hasNativeMethod($methodName)) {
            return null;
        }

        if (in_array($methodName, array_merge(self::MODEL_CREATION_METHODS, self::MODEL_RETRIEVAL_METHODS), true)) {
            $methodReflection = $eloquentBuilder->getNativeMethod($methodName);
            $parametersAcceptor = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants());
            $returnType = ModelTypeHelper::replaceStaticTypeWithModel($parametersAcceptor->getReturnType(), $modelClassName);

            return new EloquentBuilderMethodReflection(
                $methodName, $eloquentBuilder, $methodReflection,
                $parametersAcceptor->getParameters(),
                $returnType,
                $parametersAcceptor->isVariadic()
            );
        }

        return $eloquentBuilder->getNativeMethod($methodName);
    }

    public function searchOnQueryBuilder(string $methodName, string $modelClassName): ?MethodReflection
    {
        $queryBuilder = $this->reflectionProvider->getClass(QueryBuilder::class);

        if ($queryBuilder->hasNativeMethod($methodName)) {
            return $queryBuilder->getNativeMethod($methodName);
        }

        return null;
    }

    /**
     * @param string $modelClassName
     *
     * @return string
     * @throws MissingMethodFromReflectionException
     * @throws ShouldNotHappenException
     */
    public function determineBuilderType(string $modelClassName): string
    {
        $method = $this->reflectionProvider->getClass($modelClassName)->getNativeMethod('newEloquentBuilder');

        $returnType = ParametersAcceptorSelector::selectSingle($method->getVariants())->getReturnType();

        if (in_array(EloquentBuilder::class, $returnType->getReferencedClasses(), true)) {
            return EloquentBuilder::class;
        }

        if ($returnType instanceof ObjectType) {
            return $returnType->getClassName();
        }

        return $returnType->describe(VerbosityLevel::value());
    }

    public function getMethodReflectionFromBuilder(
        ClassReflection $classReflection,
        string $methodName,
        string $modelName,
        Type $customReturnType
    ): ?EloquentBuilderMethodReflection {
        $methodReflection = null;
        $model = $this->reflectionProvider->getClass($modelName);

        // This can be a custom EloquentBuilder or the normal one
        $builderName = $this->determineBuilderType($modelName);

        /** @var ClassReflection $builderReflection */
        $builderReflection = (new GenericObjectType($builderName, [new ObjectType($modelName)]))->getClassReflection();

        $methodReflection = $this->searchOnEloquentBuilder($builderReflection, $methodName, $modelName);

        if ($methodReflection === null) {
            $methodReflection = $this->searchOnQueryBuilder($methodName, $modelName);
        }

        if ($methodReflection !== null) {
            $parametersAcceptor = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants());

            // Resolve any generic models in the return type
            $returnType = TemplateTypeHelper::resolveTemplateTypes($parametersAcceptor->getReturnType(), new TemplateTypeMap(['TModelClass' => new ObjectType($modelName)]));

            // If a model scope has a void return type, return the builder
            if ($returnType instanceof VoidType && $model->hasNativeMethod('scope'.ucfirst($methodName))) {
                $returnType = $customReturnType;
            }

            $isBuilderReferenced = (
                count(array_intersect([EloquentBuilder::class, QueryBuilder::class], $returnType->getReferencedClasses())) > 0)
                || (new ObjectType(EloquentBuilder::class))->isSuperTypeOf($returnType)->yes();

            // A special case for when return type is just the `QueryBuilder`
            if ($returnType instanceof ObjectType && $returnType->getClassName() === QueryBuilder::class) {
                $isBuilderReferenced = false;
            }

            if ($isBuilderReferenced) {
                $returnType = $customReturnType;
            }

            if (! $isBuilderReferenced && (new ObjectType(Collection::class))->isSuperTypeOf($returnType)->yes()) {
                $returnType = new GenericObjectType(Collection::class, [new ObjectType($modelName)]);
            }

            $originalMethodReflection = $methodReflection;

            if ($originalMethodReflection instanceof EloquentBuilderMethodReflection) {
                $originalMethodReflection = $originalMethodReflection->getOriginalMethodReflection();
            }

            return new EloquentBuilderMethodReflection(
                $methodName, $classReflection, $originalMethodReflection,
                $parametersAcceptor->getParameters(),
                $returnType,
                $parametersAcceptor->isVariadic()
            );
        }

        return $this->dynamicWhere($methodName, $customReturnType);
    }

    public function determineCollectionClassName(string $modelClassName): string
    {
        $newCollectionMethod = $this->reflectionProvider->getClass($modelClassName)->getNativeMethod('newCollection');

        $returnType = ParametersAcceptorSelector::selectSingle($newCollectionMethod->getVariants())->getReturnType();

        if ($returnType instanceof ObjectType) {
            return $returnType->getClassName();
        }

        return $returnType->describe(VerbosityLevel::value());
    }
}
