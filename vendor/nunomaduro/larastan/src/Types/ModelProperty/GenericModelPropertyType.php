<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Types\ModelProperty;

use PHPStan\Type\ClassStringType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\Generic\TemplateType;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\Generic\TemplateTypeVariance;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;

class GenericModelPropertyType extends ModelPropertyType
{
    /** @var Type */
    private $type;

    public function __construct(Type $type)
    {
        $this->type = $type;
    }

    public function getReferencedClasses(): array
    {
        return $this->getGenericType()->getReferencedClasses();
    }

    public function getGenericType(): Type
    {
        return $this->type;
    }

    public function traverse(callable $cb): Type
    {
        $newType = $cb($this->getGenericType());

        if ($newType === $this->getGenericType()) {
            return $this;
        }

        return new self($newType);
    }

    public function inferTemplateTypes(Type $receivedType): TemplateTypeMap
    {
        if ($receivedType instanceof UnionType || $receivedType instanceof IntersectionType) {
            return $receivedType->inferTemplateTypesOn($this);
        }

        if ($receivedType instanceof ConstantStringType) {
            $typeToInfer = new ObjectType($receivedType->getValue());
        } elseif ($receivedType instanceof self) {
            $typeToInfer = $receivedType->type;
        } elseif ($receivedType instanceof ClassStringType) {
            $typeToInfer = $this->getGenericType();

            if ($typeToInfer instanceof TemplateType) {
                $typeToInfer = $typeToInfer->getBound();
            }

            $typeToInfer = TypeCombinator::intersect($typeToInfer, new ObjectWithoutClassType());
        } else {
            return TemplateTypeMap::createEmpty();
        }

        if (! $this->getGenericType()->isSuperTypeOf($typeToInfer)->no()) {
            return $this->getGenericType()->inferTemplateTypes($typeToInfer);
        }

        return TemplateTypeMap::createEmpty();
    }

    public function getReferencedTemplateTypes(TemplateTypeVariance $positionVariance): array
    {
        $variance = $positionVariance->compose(TemplateTypeVariance::createCovariant());

        return $this->getGenericType()->getReferencedTemplateTypes($variance);
    }

    /**
     * @param mixed[] $properties
     * @return Type
     */
    public static function __set_state(array $properties): Type
    {
        return new self($properties['type']);
    }
}
