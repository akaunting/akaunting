<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Methods;

use Illuminate\Database\Eloquent\Model;
use NunoMaduro\Larastan\Concerns;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\Php\PhpMethodReflectionFactory;

/**
 * @internal
 */
final class Extension implements MethodsClassReflectionExtension, BrokerAwareExtension
{
    use Concerns\HasBroker;

    /**
     * @var Kernel
     */
    private $kernel;

    /** @var MethodReflection[] */
    private $methodReflections = [];

    /**
     * Extension constructor.
     *
     * @param PhpMethodReflectionFactory $methodReflectionFactory
     * @param Kernel|null                $kernel
     */
    public function __construct(PhpMethodReflectionFactory $methodReflectionFactory, Kernel $kernel = null)
    {
        $this->kernel = $kernel ?? new Kernel($methodReflectionFactory);
    }

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if ($classReflection->getName() === Model::class) {
            return false;
        }

        if (array_key_exists($methodName.'-'.$classReflection->getName(), $this->methodReflections)) {
            return true;
        }

        $passable = $this->kernel->handle($this->broker, $classReflection, $methodName);

        $found = $passable->hasFound();

        if ($found) {
            $this->methodReflections[$methodName.'-'.$classReflection->getName()] = $passable->getMethodReflection();
        }

        return $found;
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        return $this->methodReflections[$methodName.'-'.$classReflection->getName()];
    }
}
