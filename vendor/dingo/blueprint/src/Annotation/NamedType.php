<?php

namespace Dingo\Blueprint\Annotation;

/**
 * @Annotation
 */
class NamedType
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $derivedFrom = 'object';

    /**
     * @var string
     */
    public $type = 'string';

    /**
     * @var string
     */
    public $description;

    /**
     * @array<NamedTypeProperties>
     */
    public $properties;
}
