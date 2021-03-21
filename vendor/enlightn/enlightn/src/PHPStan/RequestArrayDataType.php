<?php

declare(strict_types=1);

namespace Enlightn\Enlightn\PHPStan;

use PHPStan\TrinaryLogic;
use PHPStan\Type\ArrayType;
use PHPStan\Type\CompoundType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

class RequestArrayDataType extends ArrayType
{
    public function isSuperTypeOf(Type $type): TrinaryLogic
    {
        if ($type instanceof self) {
            return TrinaryLogic::createYes();
        }
        if ($type instanceof CompoundType) {
            return $type->isSubTypeOf($this);
        }

        return TrinaryLogic::createNo();
    }

    public function canBeSuperTypeOf(Type $type): TrinaryLogic
    {
        if ($type instanceof self) {
            return TrinaryLogic::createYes();
        }
        if ($type instanceof UnionType) {
            return $type->isSubTypeOf($this);
        }

        return TrinaryLogic::createNo();
    }

    /**
     * @param mixed[] $properties
     * @return Type
     */
    public static function __set_state(array $properties): Type
    {
        return new self($properties['keyType'], $properties['itemType']);
    }
}
