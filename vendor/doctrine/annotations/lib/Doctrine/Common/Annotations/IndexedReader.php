<?php

namespace Doctrine\Common\Annotations;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

use function call_user_func_array;
use function get_class;

/**
 * Allows the reader to be used in-place of Doctrine's reader.
 */
class IndexedReader implements Reader
{
    /** @var Reader */
    private $delegate;

    public function __construct(Reader $reader)
    {
        $this->delegate = $reader;
    }

    /**
     * {@inheritDoc}
     */
    public function getClassAnnotations(ReflectionClass $class)
    {
        $annotations = [];
        foreach ($this->delegate->getClassAnnotations($class) as $annot) {
            $annotations[get_class($annot)] = $annot;
        }

        return $annotations;
    }

    /**
     * {@inheritDoc}
     */
    public function getClassAnnotation(ReflectionClass $class, $annotation)
    {
        return $this->delegate->getClassAnnotation($class, $annotation);
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodAnnotations(ReflectionMethod $method)
    {
        $annotations = [];
        foreach ($this->delegate->getMethodAnnotations($method) as $annot) {
            $annotations[get_class($annot)] = $annot;
        }

        return $annotations;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodAnnotation(ReflectionMethod $method, $annotation)
    {
        return $this->delegate->getMethodAnnotation($method, $annotation);
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyAnnotations(ReflectionProperty $property)
    {
        $annotations = [];
        foreach ($this->delegate->getPropertyAnnotations($property) as $annot) {
            $annotations[get_class($annot)] = $annot;
        }

        return $annotations;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyAnnotation(ReflectionProperty $property, $annotation)
    {
        return $this->delegate->getPropertyAnnotation($property, $annotation);
    }

    /**
     * Proxies all methods to the delegate.
     *
     * @param string  $method
     * @param mixed[] $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->delegate, $method], $args);
    }
}
