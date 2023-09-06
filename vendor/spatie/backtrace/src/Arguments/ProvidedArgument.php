<?php

namespace Spatie\Backtrace\Arguments;

use ReflectionParameter;
use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgument;
use Spatie\Backtrace\Arguments\ReducedArgument\TruncatedReducedArgument;

class ProvidedArgument
{
    /** @var string */
    public $name;

    /** @var bool */
    public $passedByReference = false;

    /** @var bool */
    public $isVariadic = false;

    /** @var bool */
    public $hasDefaultValue = false;

    /** @var mixed */
    public $defaultValue = null;

    /** @var bool */
    public $defaultValueUsed = false;

    /** @var bool */
    public $truncated = false;

    /** @var mixed */
    public $reducedValue = null;

    /** @var string|null */
    public $originalType = null;

    public static function fromReflectionParameter(ReflectionParameter $parameter): self
    {
        return new self(
            $parameter->getName(),
            $parameter->isPassedByReference(),
            $parameter->isVariadic(),
            $parameter->isDefaultValueAvailable(),
            $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null,
        );
    }

    public static function fromNonReflectableParameter(
        int $index
    ): self {
        return new self(
            "arg{$index}",
            false,
        );
    }

    public function __construct(
        string $name,
        bool $passedByReference = false,
        bool $isVariadic = false,
        bool $hasDefaultValue = false,
        $defaultValue = null,
        bool $defaultValueUsed = false,
        bool $truncated = false,
        $reducedValue = null,
        ?string $originalType = null
    ) {
        $this->originalType = $originalType;
        $this->reducedValue = $reducedValue;
        $this->truncated = $truncated;
        $this->defaultValueUsed = $defaultValueUsed;
        $this->defaultValue = $defaultValue;
        $this->hasDefaultValue = $hasDefaultValue;
        $this->isVariadic = $isVariadic;
        $this->passedByReference = $passedByReference;
        $this->name = $name;

        if ($this->isVariadic) {
            $this->defaultValue = [];
        }
    }

    public function setReducedArgument(
        ReducedArgument $reducedArgument
    ): self {
        $this->reducedValue = $reducedArgument->value;
        $this->originalType = $reducedArgument->originalType;

        if ($reducedArgument instanceof TruncatedReducedArgument) {
            $this->truncated = true;
        }

        return $this;
    }

    public function defaultValueUsed(): self
    {
        $this->defaultValueUsed = true;
        $this->originalType = get_debug_type($this->defaultValue);

        return $this;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->defaultValueUsed
                ? $this->defaultValue
                : $this->reducedValue,
            'original_type' => $this->originalType,
            'passed_by_reference' => $this->passedByReference,
            'is_variadic' => $this->isVariadic,
            'truncated' => $this->truncated,
        ];
    }
}
