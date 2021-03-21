<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Analyser;

use function get_class;
use function gettype;
use Illuminate\Contracts\Container\Container;
use function is_object;
use NunoMaduro\Larastan\Concerns;
use NunoMaduro\Larastan\Properties\ReflectionTypeContainer;
use NunoMaduro\Larastan\Types\TypeResolver;
use PhpParser\Node\Expr;
use PHPStan\Analyser\MutatingScope as BaseScope;
use PHPStan\ShouldNotHappenException;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\Type;
use PHPStan\Type\TypehintHelper;
use ReflectionClass;

/**
 * @internal
 */
class Scope extends BaseScope
{
    use Concerns\HasContainer;

    /**
     * {@inheritdoc}
     */
    public function getType(Expr $node): Type
    {
        $type = parent::getType($node);

        if ($this->isContainer($type) && strpos(get_class($type), 'Mockery') !== 0) {
            $type = \Mockery::mock($type);
            $type->shouldReceive('isOffsetAccessible')
                ->andReturn(TrinaryLogic::createYes());
            $type->shouldReceive('hasOffsetValueType')
                ->andReturn(TrinaryLogic::createYes());
        }

        return $this->getContainer()
            ->make(TypeResolver::class)
            ->resolveFrom($type);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTypeFromArrayDimFetch(
        Expr\ArrayDimFetch $arrayDimFetch,
        Type $offsetType,
        Type $offsetAccessibleType
    ): Type {
        if ($arrayDimFetch->dim === null) {
            throw new ShouldNotHappenException();
        }

        $parentType = parent::getTypeFromArrayDimFetch($arrayDimFetch, $offsetType, $offsetAccessibleType);
        if ($this->isContainer($offsetAccessibleType)) {
            $dimType = $this->getType($arrayDimFetch->dim);
            if (! $dimType instanceof ConstantStringType) {
                return $parentType;
            }

            $concrete = $this->resolve($dimType->getValue());

            if ($concrete === null) {
                return new \PHPStan\Type\NullType();
            }

            $type = is_object($concrete) ? get_class($concrete) : gettype($concrete);

            $reflectionType = new ReflectionTypeContainer($type);

            return TypehintHelper::decideTypeFromReflection(
                $reflectionType,
                null,
                is_object($concrete) ? get_class($concrete) : null
            );
        }

        return $parentType;
    }

    /**
     * Checks if the provided type implements
     * the Illuminate Container Contract.
     *
     * @param \PHPStan\Type\Type $type
     *
     * @return bool
     * @throws \ReflectionException
     */
    private function isContainer(Type $type): bool
    {
        /** @var class-string $referencedClass */
        foreach ($type->getReferencedClasses() as $referencedClass) {
            $isClassOrInterface = class_exists($referencedClass) || interface_exists($referencedClass);
            if ($isClassOrInterface && (new ReflectionClass($referencedClass))->implementsInterface(Container::class)) {
                return true;
            }
        }

        return false;
    }
}
