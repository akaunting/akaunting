<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Types\Pipes;

use Closure;
use NunoMaduro\Larastan\Contracts\Types\PassableContract;
use NunoMaduro\Larastan\Contracts\Types\Pipes\PipeContract;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\UnionType;

/**
 * @internal
 */
final class ObjectType implements PipeContract
{
    /**
     * {@inheritdoc}
     */
    public function handle(PassableContract $passable, Closure $next): void
    {
        $type = $passable->getType();

        if ($type instanceof UnionType) {
            $types = $type->getTypes();
            foreach ($types as $key => $type) {
                if ($type instanceof ObjectWithoutClassType) {
                    $types[$key] = new MixedType();
                }
            }

            $passable->setType(new UnionType($types));
        }

        if ($type instanceof ObjectWithoutClassType) {
            $passable->setType(new MixedType());
        }

        $next($passable);
    }
}
