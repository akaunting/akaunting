<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Types\ModelProperty;

use PHPStan\Type\StringType;
use PHPStan\Type\Type;

class ModelPropertyType extends StringType
{
    /**
     * @param mixed[] $properties
     * @return Type
     */
    public static function __set_state(array $properties): Type
    {
        return new self();
    }
}
