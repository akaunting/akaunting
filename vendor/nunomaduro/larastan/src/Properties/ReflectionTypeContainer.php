<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Properties;

use ReflectionNamedType;

/**
 * @internal
 */
final class ReflectionTypeContainer extends ReflectionNamedType
{
    /**
     * @var string
     */
    private $type;

    /**
     * ReflectionTypeContainer constructor.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function allowsNull(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isBuiltin(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->type;
    }
}
