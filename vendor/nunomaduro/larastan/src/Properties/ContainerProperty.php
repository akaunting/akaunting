<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Properties;

use function get_class;
use function gettype;
use function is_object;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;
use PHPStan\Type\TypehintHelper;

/**
 * @internal
 */
final class ContainerProperty implements PropertyReflection
{
    /**
     * @var \PHPStan\Reflection\ClassReflection
     */
    private $declaringClass;

    /**
     * @var mixed
     */
    private $concrete;

    /**
     * Property constructor.
     *
     * @param \PHPStan\Reflection\ClassReflection $declaringClass
     * @param mixed $concrete
     */
    public function __construct(ClassReflection $declaringClass, $concrete)
    {
        $this->declaringClass = $declaringClass;
        $this->concrete = $concrete;
    }

    /**
     * {@inheritdoc}
     */
    public function getDeclaringClass(): ClassReflection
    {
        return $this->declaringClass;
    }

    /**
     * {@inheritdoc}
     */
    public function isStatic(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isPrivate(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isPublic(): bool
    {
        return true;
    }

    public function getType(): Type
    {
        $type = is_object($this->concrete) ? get_class($this->concrete) : gettype($this->concrete);

        $reflectionType = new ReflectionTypeContainer($type);

        return TypehintHelper::decideTypeFromReflection(
            $reflectionType,
            null,
            is_object($this->concrete) ? get_class($this->concrete) : null
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable(): bool
    {
        return true;
    }

    public function getDocComment(): ?string
    {
        return null;
    }

    public function getReadableType(): \PHPStan\Type\Type
    {
        return $this->getType();
    }

    public function getWritableType(): \PHPStan\Type\Type
    {
        return $this->getType();
    }

    public function canChangeTypeAfterAssignment(): bool
    {
        return false;
    }

    public function isDeprecated(): \PHPStan\TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function getDeprecatedDescription(): ?string
    {
        return null;
    }

    public function isInternal(): \PHPStan\TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }
}
