<?php

namespace Doctrine\Common\Annotations\Annotation;

/**
 * Annotation that can be used to signal to the parser
 * to check the types of all declared attributes during the parsing process.
 *
 * @Annotation
 */
final class Attributes
{
    /** @var array<Attribute> */
    public $value;
}
