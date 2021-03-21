<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Types;

use NunoMaduro\Larastan\Contracts\Types\PassableContract;
use PHPStan\Type\Type;

/**
 * @internal
 */
final class Passable implements PassableContract
{
    /**
     * @var \PHPStan\Type\Type
     */
    private $type;

    /**
     * Passable constructor.
     *
     * @param \PHPStan\Type\Type $type
     */
    public function __construct(Type $type)
    {
        $this->type = $type;
    }

    /**
     * @return \PHPStan\Type\Type
     */
    public function getType(): Type
    {
        return $this->type;
    }

    /**
     * @param \PHPStan\Type\Type $type
     */
    public function setType(Type $type): void
    {
        $this->type = $type;
    }
}
