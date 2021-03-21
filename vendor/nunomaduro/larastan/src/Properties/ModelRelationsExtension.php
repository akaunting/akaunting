<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Properties;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use NunoMaduro\Larastan\Concerns;
use NunoMaduro\Larastan\Methods\BuilderHelper;
use NunoMaduro\Larastan\Types\RelationParserHelper;
use PHPStan\Analyser\OutOfClassScope;
use PHPStan\Reflection\Annotations\AnnotationsPropertiesClassReflectionExtension;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NeverType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\UnionType;

/**
 * @internal
 */
final class ModelRelationsExtension implements PropertiesClassReflectionExtension, BrokerAwareExtension
{
    use Concerns\HasBroker;
    use Concerns\HasContainer;

    /** @var RelationParserHelper */
    private $relationParserHelper;

    /** @var AnnotationsPropertiesClassReflectionExtension */
    private $annotationExtension;

    /** @var BuilderHelper */
    private $builderHelper;

    public function __construct(
        RelationParserHelper $relationParserHelper,
        AnnotationsPropertiesClassReflectionExtension $annotationExtension,
        BuilderHelper $builderHelper)
    {
        $this->relationParserHelper = $relationParserHelper;
        $this->annotationExtension = $annotationExtension;
        $this->builderHelper = $builderHelper;
    }

    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        if (! $classReflection->isSubclassOf(Model::class)) {
            return false;
        }

        if ($this->annotationExtension->hasProperty($classReflection, $propertyName)) {
            return false;
        }

        $hasNativeMethod = $classReflection->hasNativeMethod($propertyName);

        if (! $hasNativeMethod) {
            return false;
        }

        $returnType = ParametersAcceptorSelector::selectSingle($classReflection->getNativeMethod($propertyName)->getVariants())->getReturnType();

        if (! (new ObjectType(Relation::class))->isSuperTypeOf($returnType)->yes()) {
            return false;
        }

        return true;
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        $method = $classReflection->getMethod($propertyName, new OutOfClassScope());

        /** @var ObjectType $returnType */
        $returnType = ParametersAcceptorSelector::selectSingle($method->getVariants())->getReturnType();

        if ($returnType instanceof GenericObjectType) {
            /** @var ObjectType $relatedModelType */
            $relatedModelType = $returnType->getTypes()[0];
            $relatedModelClassName = $relatedModelType->getClassName();
        } else {
            $relatedModelClassName = $this
                ->relationParserHelper
                ->findRelatedModelInRelationMethod($method);
        }

        if ($relatedModelClassName === null) {
            $relatedModelClassName = Model::class;
        }

        $relatedModel = new ObjectType($relatedModelClassName);
        $collectionClass = $this->builderHelper->determineCollectionClassName($relatedModelClassName);

        if (Str::contains($returnType->getClassName(), 'Many')) {
            return new ModelProperty(
                $classReflection,
                new GenericObjectType($collectionClass, [$relatedModel]),
                new NeverType(), false
            );
        }

        if (Str::endsWith($returnType->getClassName(), 'MorphTo')) {
            return new ModelProperty($classReflection, new UnionType([
                new ObjectType(Model::class),
                new MixedType(),
            ]), new NeverType(), false);
        }

        return new ModelProperty($classReflection, new UnionType([
            $relatedModel,
            new NullType(),
        ]), new NeverType(), false);
    }
}
