<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Methods;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\Storage;
use NunoMaduro\Larastan\Concerns\HasBroker;
use NunoMaduro\Larastan\Reflection\StaticMethodReflection;
use PHPStan\Analyser\OutOfClassScope;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;

class StorageMethodsClassReflectionExtension implements MethodsClassReflectionExtension, BrokerAwareExtension
{
    use HasBroker;

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if ($classReflection->getName() !== Storage::class) {
            return false;
        }

        if ($this->getBroker()->getClass(FilesystemManager::class)->hasMethod($methodName)) {
            return true;
        }

        if ($this->getBroker()->getClass(FilesystemAdapter::class)->hasMethod($methodName)) {
            return true;
        }

        return false;
    }

    public function getMethod(
        ClassReflection $classReflection,
        string $methodName
    ): MethodReflection {
        if ($this->getBroker()->getClass(FilesystemManager::class)->hasMethod($methodName)) {
            return new StaticMethodReflection(
                $this->getBroker()->getClass(FilesystemManager::class)->getMethod($methodName, new OutOfClassScope())
            );
        }

        return new StaticMethodReflection(
            $this->getBroker()->getClass(FilesystemAdapter::class)->getMethod($methodName, new OutOfClassScope())
        );
    }
}
