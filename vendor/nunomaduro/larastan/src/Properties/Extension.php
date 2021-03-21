<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Properties;

use function get_class;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Str;
use NunoMaduro\Larastan\Concerns;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;

/**
 * @internal
 */
final class Extension implements PropertiesClassReflectionExtension, BrokerAwareExtension
{
    use Concerns\HasContainer, Concerns\HasBroker;

    /**
     * {@inheritdoc}
     */
    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        $hasProperty = false;

        if ($classReflection->isInterface() && Str::startsWith($classReflection->getName(), 'Illuminate\Contracts')) {
            $concrete = $this->resolve($classReflection->getName());

            if ($concrete !== null) {

                /*
                 * @todo Consider refactor
                 */
                switch ($concrete) {
                    case $concrete instanceof Container:
                        $hasProperty = $this->resolve($propertyName) !== null;
                        break;
                    default:
                        $hasProperty = $this->broker->getClass(get_class($concrete))
                            ->hasProperty($propertyName);
                }
            }
        }

        return $hasProperty;
    }

    /**
     * {@inheritdoc}
     */
    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        $concrete = $this->resolve($classReflection->getName());

        switch ($concrete) {
            case $concrete instanceof Container:
                $propertyValue = $this->resolve($propertyName);
                $property = new ContainerProperty(
                    $classReflection, $propertyValue
                );
                break;
            default:
                $property = $this->broker->getClass(get_class($concrete))
                    ->getNativeProperty($propertyName);
        }

        return $property;
    }
}
