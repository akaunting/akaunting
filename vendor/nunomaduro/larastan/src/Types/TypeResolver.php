<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Types;

use Illuminate\Pipeline\Pipeline;
use NunoMaduro\Larastan\Concerns;
use NunoMaduro\Larastan\Types\Pipes\ObjectType;
use PHPStan\Type\Type;

/**
 * @internal
 */
final class TypeResolver
{
    use Concerns\HasContainer;

    /**
     * @param \PHPStan\Type\Type $type
     * @return \PHPStan\Type\Type
     */
    public function resolveFrom(Type $type): Type
    {
        $pipeline = new Pipeline($this->getContainer());

        $passable = new Passable($type);

        $pipeline->send($passable)
            ->through(
                [
                    ObjectType::class,
                ]
            )
            ->then(
                function ($passable) {
                }
            );

        return $passable->getType();
    }
}
