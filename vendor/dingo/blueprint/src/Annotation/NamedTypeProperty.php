<?php

namespace Dingo\Blueprint\Annotation;

/**
 * @Annotation
 */
class NamedTypeProperty
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type = 'string';

    /**
     * @var string
     */
    public $sample;
}
