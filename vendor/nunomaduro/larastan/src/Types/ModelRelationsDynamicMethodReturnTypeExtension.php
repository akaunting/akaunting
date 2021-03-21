<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Types;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use NunoMaduro\Larastan\Concerns\HasContainer;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class ModelRelationsDynamicMethodReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    use HasContainer;

    /** @var RelationParserHelper */
    private $relationParserHelper;

    public function __construct(RelationParserHelper $relationParserHelper)
    {
        $this->relationParserHelper = $relationParserHelper;
    }

    public function getClass(): string
    {
        return Model::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        $variants = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants());

        $returnType = $variants->getReturnType();

        if (! $returnType instanceof ObjectType) {
            return false;
        }

        if (! (new ObjectType(Relation::class))->isSuperTypeOf($returnType)->yes()) {
            return false;
        }

        if (! $methodReflection->getDeclaringClass()->hasNativeMethod($methodReflection->getName())) {
            return false;
        }

        if (count($variants->getParameters()) !== 0) {
            return false;
        }

        if (in_array($methodReflection->getName(), [
            'hasOne', 'hasOneThrough', 'morphOne',
            'belongsTo', 'morphTo',
            'hasMany', 'hasManyThrough', 'morphMany',
            'belongsToMany', 'morphToMany', 'morphedByMany',
        ], true)) {
            return false;
        }

        $relatedModel = $this
            ->relationParserHelper
            ->findRelatedModelInRelationMethod($methodReflection);

        return $relatedModel !== null;
    }

    /**
     * @param MethodReflection $methodReflection
     * @param MethodCall       $methodCall
     * @param Scope            $scope
     *
     * @return Type
     * @throws ShouldNotHappenException
     */
    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): Type {
        /** @var ObjectType $returnType */
        $returnType = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants())->getReturnType();

        /** @var string $relatedModelClassName */
        $relatedModelClassName = $this
            ->relationParserHelper
            ->findRelatedModelInRelationMethod($methodReflection);

        $classReflection = $methodReflection->getDeclaringClass();

        if ($returnType->isInstanceOf(BelongsTo::class)->yes()) {
            return new GenericObjectType($returnType->getClassName(), [
                new ObjectType($relatedModelClassName),
                new ObjectType($classReflection->getName()),
            ]);
        }

        return new GenericObjectType($returnType->getClassName(), [new ObjectType($relatedModelClassName)]);
    }
}
