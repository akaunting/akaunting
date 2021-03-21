<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Methods\Pipes;

use Carbon\Traits\Macro as CarbonMacro;
use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use NunoMaduro\Larastan\Concerns;
use NunoMaduro\Larastan\Contracts\Methods\PassableContract;
use NunoMaduro\Larastan\Contracts\Methods\Pipes\PipeContract;
use NunoMaduro\Larastan\Methods\Macro;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\Generic\TemplateTypeMap;

/**
 * @internal
 */
final class Macros implements PipeContract
{
    use Concerns\HasContainer;

    private function hasIndirectTraitUse(ClassReflection $class, string $traitName): bool
    {
        foreach ($class->getTraits() as $trait) {
            if ($this->hasIndirectTraitUse($trait, $traitName)) {
                return true;
            }
        }

        return $class->hasTraitUse($traitName);
    }

    /**
     * {@inheritdoc}
     */
    public function handle(PassableContract $passable, Closure $next): void
    {
        $classReflection = $passable->getClassReflection();

        /** @var class-string $className */
        $className = null;
        $found = false;
        $macroTraitProperty = null;

        if ($classReflection->isInterface() && Str::startsWith($classReflection->getName(), 'Illuminate\Contracts')) {
            $concrete = $this->resolve($classReflection->getName());

            if ($concrete !== null) {
                $className = get_class($concrete);

                if ($passable->getBroker()
                    ->getClass($className)
                    ->hasTraitUse(Macroable::class)) {
                    $macroTraitProperty = 'macros';
                }
            }
        } elseif ($classReflection->hasTraitUse(Macroable::class)) {
            $className = $classReflection->getName();
            $macroTraitProperty = 'macros';
        } elseif ($this->hasIndirectTraitUse($classReflection, CarbonMacro::class)) {
            $className = $classReflection->getName();
            $macroTraitProperty = 'globalMacros';
        }

        if ($className !== null && $macroTraitProperty) {
            $refObject = new \ReflectionClass($className);
            $refProperty = $refObject->getProperty($macroTraitProperty);
            $refProperty->setAccessible(true);

            $className = (string) $className;

            if ($found = $className::hasMacro($passable->getMethodName())) {
                $reflectionFunction = new \ReflectionFunction($refProperty->getValue()[$passable->getMethodName()]);
                /** @var \PHPStan\Type\Type[] $parameters */
                $parameters = $reflectionFunction->getParameters();
                $methodReflection = new Macro(
                    $className, $passable->getMethodName(), $reflectionFunction
                );

                $methodReflection->setIsStatic(true);

                $passable->setMethodReflection(
                    $passable->getMethodReflectionFactory()
                        ->create(
                            $classReflection,
                            null,
                            $methodReflection,
                            TemplateTypeMap::createEmpty(),
                            $parameters,
                            null,
                            null,
                            null,
                            false,
                            false,
                            false,
                            null
                        )
                );
            }
        }

        if (! $found) {
            $next($passable);
        }
    }
}
