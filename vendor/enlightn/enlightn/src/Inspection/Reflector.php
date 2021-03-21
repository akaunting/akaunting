<?php

namespace Enlightn\Enlightn\Inspection;

use ReflectionClass;

class Reflector
{
    /**
     * @param mixed $instance
     * @param string $propertyName
     * @return mixed
     * @throws \ReflectionException
     */
    public static function get($instance, string $propertyName)
    {
        $mirror = new ReflectionClass($instance);
        $property = $mirror->getProperty($propertyName);
        $property->setAccessible(true);

        $value = $property->getValue($instance);
        $property->setAccessible(false);

        return $value;
    }

    /**
     * @param mixed $instance
     * @param string $propertyName
     * @param mixed $value
     * @return void
     * @throws \ReflectionException
     */
    public static function set($instance, string $propertyName, $value)
    {
        $mirror = new ReflectionClass($instance);
        $property = $mirror->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($instance, $value);
        $property->setAccessible(false);
    }
}
