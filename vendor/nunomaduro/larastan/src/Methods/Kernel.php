<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Methods;

use Illuminate\Pipeline\Pipeline;
use NunoMaduro\Larastan\Concerns;
use NunoMaduro\Larastan\Contracts\Methods\PassableContract;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Php\PhpMethodReflectionFactory;

/**
 * @internal
 */
final class Kernel
{
    use Concerns\HasContainer;

    /**
     * @var PhpMethodReflectionFactory
     */
    private $methodReflectionFactory;

    /**
     * Kernel constructor.
     *
     * @param PhpMethodReflectionFactory $methodReflectionFactory
     */
    public function __construct(
        PhpMethodReflectionFactory $methodReflectionFactory
    ) {
        $this->methodReflectionFactory = $methodReflectionFactory;
    }

    /**
     * @param Broker          $broker
     * @param ClassReflection $classReflection
     * @param string          $methodName
     *
     * @return PassableContract
     */
    public function handle(Broker $broker, ClassReflection $classReflection, string $methodName): PassableContract
    {
        $pipeline = new Pipeline($this->getContainer());

        $passable = new Passable($this->methodReflectionFactory, $broker, $pipeline, $classReflection, $methodName);

        $pipeline->send($passable)
            ->through(
                [
                    Pipes\SelfClass::class,
                    Pipes\Macros::class,
                    Pipes\Contracts::class,
                    Pipes\Facades::class,
                    Pipes\Managers::class,
                    Pipes\Auths::class,
                    Pipes\BuilderLocalMacros::class,
                    Pipes\RedirectResponseWiths::class,
                ]
            )
            ->then(
                function ($method) {
                }
            );

        return $passable;
    }
}
