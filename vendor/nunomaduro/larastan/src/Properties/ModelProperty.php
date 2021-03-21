<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Properties;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;

class ModelProperty implements PropertyReflection
{
    /** @var ClassReflection */
    private $declaringClass;

    /** @var Type */
    private $readableType;

    /** @var Type */
    private $writableType;

    /** @var bool */
    private $writeable;

    public function __construct(ClassReflection $declaringClass, Type $readableType, Type $writableType, bool $writeable = true)
    {
        $this->declaringClass = $declaringClass;
        $this->readableType = $readableType;
        $this->writableType = $writableType;
        $this->writeable = $writeable;
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->declaringClass;
    }

    public function isStatic(): bool
    {
        return false;
    }

    public function isPrivate(): bool
    {
        return false;
    }

    public function isPublic(): bool
    {
        return true;
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function isWritable(): bool
    {
        return $this->writeable;
    }

    public function getDocComment(): ?string
    {
        return null;
    }

    public function getReadableType(): Type
    {
        return $this->readableType;
    }

    public function getWritableType(): Type
    {
        return $this->writableType;
    }

    public function canChangeTypeAfterAssignment(): bool
    {
        return false;
    }

    public function isDeprecated(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function getDeprecatedDescription(): ?string
    {
        return null;
    }

    public function isInternal(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }
}
