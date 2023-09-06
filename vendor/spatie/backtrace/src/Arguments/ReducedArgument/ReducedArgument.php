<?php

namespace Spatie\Backtrace\Arguments\ReducedArgument;

class ReducedArgument implements ReducedArgumentContract
{
    /** @var mixed */
    public $value;

    /** @var string */
    public $originalType;

    /**
     * @param mixed $value
     */
    public function __construct(
        $value,
        string $originalType
    ) {
        $this->originalType = $originalType;
        $this->value = $value;
    }
}
